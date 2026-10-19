<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMutation;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $totalCategories = Category::count();

        // Low stock items
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->get();
        $lowStockCount = $lowStockProducts->count();

        // Recent Stock In / Restock
        $recentRestocks = StockMutation::with(['product', 'user'])
            ->latest()
            ->limit(8)
            ->get();

        return view('gudang.dashboard', compact(
            'totalProducts',
            'totalStock',
            'totalCategories',
            'lowStockProducts',
            'lowStockCount',
            'recentRestocks'
        ));
    }
}
