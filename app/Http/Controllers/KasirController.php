<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function pos()
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('kasir.pos', compact('categories', 'products'));
    }

    public function apiProducts(Request $request)
    {
        $query = Product::with('category')
            ->where('is_active', true);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('name')->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'code' => $p->code,
                'name' => $p->name,
                'category_name' => $p->category ? $p->category->name : '-',
                'cost_price' => $p->cost_price,
                'selling_price' => $p->selling_price,
                'formatted_price' => 'Rp ' . number_format($p->selling_price, 0, ',', '.'),
                'stock' => $p->stock,
                'unit' => $p->unit,
                'is_low' => $p->isLowStock(),
                'image_url' => $p->image ? asset('storage/' . $p->image) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:cash,qris,transfer'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'customer_name' => ['nullable', 'string', 'max:100'],
        ]);

        $items = $request->items;
        $paymentMethod = $request->payment_method;
        $paidAmount = (int) $request->paid_amount;
        $customerName = !empty(trim($request->customer_name)) ? trim($request->customer_name) : 'Pelanggan Umum';

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $detailsToInsert = [];

            foreach ($items as $item) {
                $product = Product::lockForUpdate()->find($item['id']);

                if (!$product || !$product->is_active) {
                    throw new \Exception("Barang dengan ID {$item['id']} tidak aktif atau tidak ditemukan.");
                }

                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok barang '{$product->name}' tidak mencukupi (Tersisa: {$product->stock} {$product->unit}).");
                }

                $itemSubtotal = $product->selling_price * $item['qty'];
                $subtotal += $itemSubtotal;

                // Decrease stock
                $product->decrement('stock', $item['qty']);

                $detailsToInsert[] = [
                    'product_id' => $product->id,
                    'product_code' => $product->code,
                    'product_name' => $product->name,
                    'cost_price' => $product->cost_price,
                    'selling_price' => $product->selling_price,
                    'qty' => $item['qty'],
                    'subtotal' => $itemSubtotal,
                ];
            }

            $discount = 0;
            $totalAmount = $subtotal - $discount;

            if ($paymentMethod === 'cash' && $paidAmount < $totalAmount) {
                throw new \Exception("Nominal pembayaran tunai kurang dari total tagihan (Total: Rp " . number_format($totalAmount, 0, ',', '.') . ").");
            }

            // If non-cash, paid amount equals total amount if not specified
            if ($paymentMethod !== 'cash') {
                $paidAmount = $totalAmount;
            }

            $changeAmount = max(0, $paidAmount - $totalAmount);

            // Generate unique invoice code KSK-YYYYMMDD-XXXX
            $datePrefix = Carbon::now()->format('Ymd');
            $todayCount = Transaction::whereDate('created_at', Carbon::today())->count() + 1;
            $invoiceCode = 'KSK-' . $datePrefix . '-' . str_pad($todayCount, 4, '0', STR_PAD_LEFT);

            // Ensure uniqueness
            while (Transaction::where('invoice_code', $invoiceCode)->exists()) {
                $todayCount++;
                $invoiceCode = 'KSK-' . $datePrefix . '-' . str_pad($todayCount, 4, '0', STR_PAD_LEFT);
            }

            $transaction = Transaction::create([
                'invoice_code' => $invoiceCode,
                'user_id' => Auth::id(),
                'customer_name' => $customerName,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'payment_method' => $paymentMethod,
                'status' => 'completed',
            ]);

            foreach ($detailsToInsert as $detail) {
                $detail['transaction_id'] = $transaction->id;
                $detail['created_at'] = now();
                $detail['updated_at'] = now();
                TransactionDetail::create($detail);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses!',
                'transaction' => [
                    'id' => $transaction->id,
                    'invoice_code' => $transaction->invoice_code,
                    'date' => $transaction->created_at->format('d/m/Y H:i'),
                    'cashier' => Auth::user()->name,
                    'customer' => $transaction->customer_name,
                    'total' => $transaction->total_amount,
                    'paid' => $transaction->paid_amount,
                    'change' => $transaction->change_amount,
                    'payment_method' => strtoupper($transaction->payment_method),
                    'items' => $detailsToInsert,
                    'print_url' => route('kasir.receipt', $transaction->id),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function transactions(Request $request)
    {
        // Kasir only sees their own transactions
        $query = Transaction::with('details')
            ->where('user_id', Auth::id())
            ->latest();

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        } else {
            // Default to today's shift
            $query->whereDate('created_at', Carbon::today());
        }

        $myTransactions = $query->paginate(15)->withQueryString();

        $shiftSummary = Transaction::where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->selectRaw('count(*) as count, coalesce(sum(total_amount), 0) as total')
            ->first();

        return view('kasir.transactions', compact('myTransactions', 'shiftSummary'));
    }

    public function receipt($id)
    {
        $transaction = Transaction::with(['user', 'details'])->findOrFail($id);

        // Kasir can only view their own receipt, owner can view any
        if (Auth::user()->role === 'kasir' && $transaction->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses melihat struk transaksi kasir lain.');
        }

        return view('kasir.receipt', compact('transaction'));
    }
}
