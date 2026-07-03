<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with summary metrics and monthly chart data.
     * Accessible only by Admin and Manager.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-dashboard');

        // Summary cards
        $totalProducts     = Product::count();
        $availableProducts = Product::where('stock', '>', 0)->count();
        $borrowedCount     = BorrowingDetail::whereNull('returned_at')->count();
        $activeBorrowings  = Borrowing::where('status', 'borrowed')->count();

        // Monthly borrowing chart — current year, grouped by month
        $monthlyData = Borrowing::select(
                DB::raw('MONTH(borrow_date) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('borrow_date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Build a full 12-month array (zero-filled for months with no data)
        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartData[] = $monthlyData[$m] ?? 0;
        }

        return view('dashboard', compact(
            'totalProducts',
            'availableProducts',
            'borrowedCount',
            'activeBorrowings',
            'chartData'
        ));
    }
}
