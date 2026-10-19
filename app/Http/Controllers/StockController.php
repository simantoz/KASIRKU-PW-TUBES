<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMutation::with(['product.category', 'user'])->latest();

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $mutations = $query->paginate(15)->withQueryString();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        $totalStockInQty = (clone $query)->sum('qty');
        $totalStockInCost = (clone $query)->sum('total_cost');

        return view('gudang.stock.index', compact(
            'mutations',
            'products',
            'totalStockInQty',
            'totalStockInCost'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'qty' => ['required', 'integer', 'min:1'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($validated['product_id']);
            $totalCost = (int)$validated['qty'] * (int)$validated['cost_price'];

            StockMutation::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'in',
                'supplier_name' => $validated['supplier_name'] ?? 'Supplier Umum',
                'qty' => $validated['qty'],
                'cost_price' => $validated['cost_price'],
                'total_cost' => $totalCost,
                'notes' => $validated['notes'] ?? 'Penerimaan stok barang masuk',
            ]);

            // Increment product stock and update cost price
            $product->increment('stock', $validated['qty']);
            $product->update(['cost_price' => $validated['cost_price']]);

            DB::commit();

            return redirect()->route('gudang.stock.index')
                ->with('success', "Stok barang '{$product->name}' berhasil ditambah sebanyak {$validated['qty']} {$product->unit}!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses stok masuk: ' . $e->getMessage());
        }
    }
}
