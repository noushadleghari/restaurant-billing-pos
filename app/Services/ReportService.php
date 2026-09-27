<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function summary(Carbon $from, Carbon $to): array
    {
        $orders = Order::completed()->whereBetween('completed_at', [$from, $to]);

        return [
            'total_sales' => (clone $orders)->sum('total'),
            'total_orders' => (clone $orders)->count(),
            'avg_order_value' => (clone $orders)->count() > 0
                ? round((clone $orders)->sum('total') / (clone $orders)->count(), 2)
                : 0,
            'total_discount' => (clone $orders)->sum('discount'),
        ];
    }

    public function salesByDay(Carbon $from, Carbon $to)
    {
        return Order::completed()
            ->whereBetween('completed_at', [$from, $to])
            ->select(DB::raw('DATE(completed_at) as date'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as orders_count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function topProducts(Carbon $from, Carbon $to, int $limit = 8)
    {
        return OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.completed_at', [$from, $to])
            ->select('order_items.product_name', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->get();
    }

    public function paymentMethodBreakdown(Carbon $from, Carbon $to)
    {
        return Order::completed()
            ->whereBetween('completed_at', [$from, $to])
            ->select('payment_method', DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as orders_count'))
            ->groupBy('payment_method')
            ->get();
    }
}
