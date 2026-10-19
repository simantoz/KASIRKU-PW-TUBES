<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OwnerController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Summary KPI
        $revenueToday = Transaction::whereDate('created_at', $today)->where('status', 'completed')->sum('total_amount');
        $revenueThisMonth = Transaction::where('created_at', '>=', $thisMonth)->where('status', 'completed')->sum('total_amount');
        $totalRevenue = Transaction::where('status', 'completed')->sum('total_amount');
        
        $transactionsToday = Transaction::whereDate('created_at', $today)->count();
        $totalTransactions = Transaction::count();

        $totalProducts = Product::count();
        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')->count();
        $totalEmployees = User::whereIn('role', ['kasir', 'gudang'])->count();

        // 7 Days Chart Data
        $chartDates = [];
        $chartRevenues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartDates[] = $date->translatedFormat('d M');
            $chartRevenues[] = (int) Transaction::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total_amount');
        }

        // Payment Method breakdown
        $paymentMethods = Transaction::select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
            ->where('status', 'completed')
            ->groupBy('payment_method')
            ->get();

        // Top 5 Best Selling Products
        $topProducts = TransactionDetail::select('product_name', DB::raw('sum(qty) as total_qty'), DB::raw('sum(subtotal) as total_sales'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Recent 6 Transactions
        $recentTransactions = Transaction::with(['user', 'details'])
            ->latest()
            ->limit(6)
            ->get();

        return view('owner.dashboard', compact(
            'revenueToday',
            'revenueThisMonth',
            'totalRevenue',
            'transactionsToday',
            'totalTransactions',
            'totalProducts',
            'lowStockCount',
            'totalEmployees',
            'chartDates',
            'chartRevenues',
            'paymentMethods',
            'topProducts',
            'recentTransactions'
        ));
    }

    public function transactions(Request $request)
    {
        $query = Transaction::with(['user', 'details'])->latest();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $cashiers = User::where('role', 'kasir')->get();

        $filteredTransactions = (clone $query)->get();
        $totalRevenue = $filteredTransactions->sum('total_amount');
        $totalTransactionsCount = $filteredTransactions->count();

        $transactions = $query->paginate(15)->withQueryString();

        return view('owner.transactions', compact(
            'transactions',
            'cashiers',
            'totalRevenue',
            'totalTransactionsCount'
        ));
    }

    public function showTransaction($id)
    {
        $transaction = Transaction::with(['user', 'details.product'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $transaction,
        ]);
    }

    public function exportCsv(Request $request)
    {
        $query = Transaction::with('user')->latest();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->get();

        $filename = 'Laporan_Penjualan_KasirKu_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'No Invoice',
                'Tanggal & Waktu',
                'Kasir',
                'Nama Pelanggan',
                'Metode Pembayaran',
                'Subtotal (Rp)',
                'Diskon (Rp)',
                'Total Bayar (Rp)',
                'Uang Diterima (Rp)',
                'Kembalian (Rp)',
                'Status'
            ], ';');

            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->invoice_code,
                    $t->created_at->format('d/m/Y H:i:s'),
                    $t->user ? $t->user->name : 'N/A',
                    $t->customer_name,
                    strtoupper($t->payment_method),
                    $t->subtotal,
                    $t->discount,
                    $t->total_amount,
                    $t->paid_amount,
                    $t->change_amount,
                    ucfirst($t->status)
                ], ';');
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function printReport(Request $request)
    {
        $query = Transaction::with(['user', 'details'])->latest();

        $dateFrom = $request->date_from ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? Carbon::now()->format('Y-m-d');

        $query->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->get();
        $totalRevenue = $transactions->sum('total_amount');
        $totalPaid = $transactions->sum('paid_amount');
        $totalChange = $transactions->sum('change_amount');

        return view('owner.print_report', compact(
            'transactions',
            'dateFrom',
            'dateTo',
            'totalRevenue',
            'totalPaid',
            'totalChange'
        ));
    }
}
