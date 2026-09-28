<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Net sale expression: the order's total minus whatever was refunded
     * from it (0 if nothing was refunded). This is what every sales figure
     * in reports is built from, so a refund immediately and correctly
     * reduces sales instead of just disappearing the whole order.
     */
    protected function netTotalExpr(): string
    {
        return '(orders.total - COALESCE(orders.refund_amount, 0))';
    }

    public function summary(Carbon $from, Carbon $to): array
    {
        $orders = Order::completedOrRefunded()->whereBetween('completed_at', [$from, $to]);

        $count = (clone $orders)->count();
        $netSales = (clone $orders)->sum(DB::raw($this->netTotalExpr()));

        return [
            'total_sales' => $netSales,
            'total_orders' => $count,
            'avg_order_value' => $count > 0 ? round($netSales / $count, 2) : 0,
            'total_discount' => (clone $orders)->sum('discount'),
            'total_refunds' => (clone $orders)->sum('refund_amount'),
        ];
    }

    public function salesByDay(Carbon $from, Carbon $to)
    {
        return Order::completedOrRefunded()
            ->whereBetween('completed_at', [$from, $to])
            ->select(
                DB::raw('DATE(completed_at) as date'),
                DB::raw('SUM'.$this->netTotalExpr().' as total'),
                DB::raw('COUNT(*) as orders_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function topProducts(Carbon $from, Carbon $to, int $limit = 8)
    {
        // Item-level quantities/revenue reflect what was actually rung up and
        // prepared, so completed + refunded orders both count here (a refund
        // is a financial reversal, not proof the item wasn't made/served).
        return OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status', ['completed', 'refunded'])
            ->whereBetween('orders.completed_at', [$from, $to])
            ->select('order_items.product_name', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->get();
    }

    public function paymentMethodBreakdown(Carbon $from, Carbon $to)
    {
        return Order::completedOrRefunded()
            ->whereBetween('completed_at', [$from, $to])
            ->select('payment_method', DB::raw('SUM'.$this->netTotalExpr().' as total'), DB::raw('COUNT(*) as orders_count'))
            ->groupBy('payment_method')
            ->get();
    }
}
