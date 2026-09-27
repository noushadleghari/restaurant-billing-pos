<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use App\Models\Order;
use App\Models\Product;
use App\Services\ReportService;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke(ReportService $reports)
    {
        $today = Carbon::today();
        $todaySummary = $reports->summary($today->copy()->startOfDay(), $today->copy()->endOfDay());

        $yesterday = $today->copy()->subDay();
        $yesterdaySummary = $reports->summary($yesterday->copy()->startOfDay(), $yesterday->copy()->endOfDay());

        $recentOrders = Order::with(['diningTable', 'cashier'])
            ->completed()
            ->latest('completed_at')
            ->limit(8)
            ->get();

        $openOrders = Order::where('status', 'open')->count();
        $occupiedTables = DiningTable::where('status', 'occupied')->count();
        $totalTables = DiningTable::where('is_active', true)->count();
        $lowAvailability = Product::where('is_available', false)->count();
        $topProducts = $reports->topProducts($today->copy()->startOfDay(), $today->copy()->endOfDay(), 5);

        return view('dashboard.index', compact(
            'todaySummary', 'yesterdaySummary', 'recentOrders',
            'openOrders', 'occupiedTables', 'totalTables', 'lowAvailability', 'topProducts'
        ));
    }
}
