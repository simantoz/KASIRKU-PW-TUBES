<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\StockMutation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['user', 'details.product'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('purchase_code', 'like', "%{$s}%")
                  ->orWhere('supplier_name', 'like', "%{$s}%")
                  ->orWhere('invoice_ref', 'like', "%{$s}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $purchases = $query->paginate(15)->withQueryString();

        $totalSpent = (clone $query)->sum('total_amount');
        $totalPurchasesCount = (clone $query)->count();

        return view('gudang.purchases.index', compact(
            'purchases',
            'totalSpent',
            'totalPurchasesCount'
        ));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::where('is_active', true)->with('category')->orderBy('name')->get();

        return view('gudang.purchases.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => ['required', 'string', 'max:255'],
            'invoice_ref' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.cost_price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::beginTransaction();
        try {
            // Generate purchase_code PO-YYYYMMDD-XXXX
            $datePrefix = Carbon::now()->format('Ymd');
            $todayCount = Purchase::whereDate('created_at', Carbon::today())->count();
            $poCode = 'PO-' . $datePrefix . '-' . str_pad($todayCount + 1, 4, '0', STR_PAD_LEFT);

            $totalAmount = 0;
            $detailsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['id']);
                $qty = (int)$item['qty'];
                $costPrice = (int)$item['cost_price'];
                $subtotal = $qty * $costPrice;
                $totalAmount += $subtotal;

                $detailsData[] = [
                    'product_id' => $product->id,
                    'product_code' => $product->code,
                    'product_name' => $product->name,
                    'cost_price' => $costPrice,
                    'qty' => $qty,
                    'subtotal' => $subtotal,
                ];

                // Increment Product Stock and update Cost Price
                $product->increment('stock', $qty);
                $product->update(['cost_price' => $costPrice]);

                // Create StockMutation record for legacy audit trail
                StockMutation::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'supplier_name' => $validated['supplier_name'],
                    'qty' => $qty,
                    'cost_price' => $costPrice,
                    'total_cost' => $subtotal,
                    'notes' => 'Faktur PO: ' . $poCode . ($validated['invoice_ref'] ? ' (Ref: ' . $validated['invoice_ref'] . ')' : ''),
                ]);
            }

            $purchase = Purchase::create([
                'purchase_code' => $poCode,
                'user_id' => Auth::id(),
                'supplier_name' => $validated['supplier_name'],
                'invoice_ref' => $validated['invoice_ref'] ?? null,
                'total_amount' => $totalAmount,
                'status' => 'received',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($detailsData as $detail) {
                $detail['purchase_id'] = $purchase->id;
                PurchaseDetail::create($detail);
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi barang masuk berhasil disimpan!',
                    'purchase' => [
                        'id' => $purchase->id,
                        'purchase_code' => $purchase->purchase_code,
                        'supplier_name' => $purchase->supplier_name,
                        'total_amount' => $purchase->total_amount,
                        'print_url' => route('gudang.purchases.print', $purchase->id),
                    ]
                ]);
            }

            return redirect()->route('gudang.purchases.index')
                ->with('success', "Transaksi barang masuk {$purchase->purchase_code} berhasil diproses!");
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->with('error', 'Gagal memproses barang masuk: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $purchase = Purchase::with(['user', 'details.product'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $purchase,
        ]);
    }

    public function printNote($id)
    {
        $purchase = Purchase::with(['user', 'details'])->findOrFail($id);

        return view('gudang.purchases.print', compact('purchase'));
    }
}
