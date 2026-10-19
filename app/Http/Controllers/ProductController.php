<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0);
            } elseif ($request->stock_status === 'out') {
                $query->where('stock', '<=', 0);
            }
        }

        $categories = Category::orderBy('name')->get();
        $products = $query->paginate(12)->withQueryString();

        return view('gudang.products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'code' => ['required', 'string', 'max:50', 'unique:products,code'],
            'name' => ['required', 'string', 'max:255'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:20'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        Product::create($validated);

        return redirect()->route('gudang.products.index')
            ->with('success', 'Data barang berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'code' => ['required', 'string', 'max:50', Rule::unique('products', 'code')->ignore($product->id)],
            'name' => ['required', 'string', 'max:255'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:20'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('gudang.products.index')
            ->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        // Check if product is in transactions
        if ($product->transactionDetails()->count() > 0) {
            $product->update(['is_active' => false]);
            return redirect()->route('gudang.products.index')
                ->with('info', 'Barang ini telah memiliki riwayat transaksi, sehingga statusnya dinonaktifkan (arsip) agar tidak merusak data transaksi.');
        }

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('gudang.products.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
