<?php

namespace App\Services;

use App\Models\DiningTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Create a new order OR update the existing open order for a table (save/hold).
     */
    public function saveOrder(array $data, ?Order $existingOrder = null): Order
    {
        return DB::transaction(function () use ($data, $existingOrder) {
            $order = $existingOrder ?? new Order();
            $order->order_type = $data['order_type'] ?? 'dine_in';
            $order->dining_table_id = $data['dining_table_id'] ?? null;
            $order->customer_id = $data['customer_id'] ?? null;
            $order->user_id = $data['user_id'] ?? auth()->id();
            $order->note = $data['note'] ?? null;
            $order->status = 'open';

            if (empty($order->order_number)) {
                $order->order_number = $this->generateOrderNumber();
            }

            $order->save();

            // Replace items
            $order->items()->delete();

            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];
                $lineTotal = round($product->price * $qty, 2);
                $subtotal += $lineTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $qty,
                    'subtotal' => $lineTotal,
                    'note' => $item['note'] ?? null,
                ]);
            }

            // Treat discount as a percentage (clamped between 0 and 100)
            $discountPercent = max(0, min(100, (float) ($data['discount'] ?? 0)));
            $discountAmount  = round($subtotal * ($discountPercent / 100), 2);

            $taxPercent = max(0, (float) ($data['tax_percent'] ?? 0));
            $taxable    = max($subtotal - $discountAmount, 0);
            $taxAmount  = round($taxable * ($taxPercent / 100), 2);

            $order->subtotal    = $subtotal;
            $order->discount    = $discountPercent; // Stores the literal percentage (e.g., 10)
            $order->tax_percent = $taxPercent;
            $order->tax_amount  = $taxAmount;
            $order->total       = round($taxable + $taxAmount, 2);
            $order->save();

            if ($order->dining_table_id) {
                DiningTable::where('id', $order->dining_table_id)->update(['status' => 'occupied']);
            }

            return $order->fresh('items.product', 'diningTable', 'customer');
        });
    }

    public function checkout(Order $order, array $paymentData): Order
    {
        return DB::transaction(function () use ($order, $paymentData) {
            if (isset($paymentData['discount']) || isset($paymentData['tax_percent'])) {
                $subtotal = (float) $order->subtotal;

                // Use new discount percentage if provided, otherwise preserve existing discount percentage
                $discountPercent = max(0, min(100, (float) ($paymentData['discount'] ?? $order->discount)));
                $discountAmount  = round($subtotal * ($discountPercent / 100), 2);

                $taxPercent = max(0, (float) ($paymentData['tax_percent'] ?? $order->tax_percent));
                $taxable    = max($subtotal - $discountAmount, 0);
                $taxAmount  = round($taxable * ($taxPercent / 100), 2);

                $order->discount    = $discountPercent; // Stores the literal percentage
                $order->tax_percent = $taxPercent;
                $order->tax_amount  = $taxAmount;
                $order->total       = round($taxable + $taxAmount, 2);
            }

            $order->payment_method = $paymentData['payment_method'];
            $order->paid_amount    = $paymentData['paid_amount'];
            $order->change_amount  = max($paymentData['paid_amount'] - $order->total, 0);
            $order->status         = 'completed';
            $order->completed_at   = now();
            $order->save();

            if ($order->dining_table_id) {
                DiningTable::where('id', $order->dining_table_id)->update(['status' => 'available']);
            }

            return $order->fresh('items', 'diningTable', 'customer', 'cashier');
        });
    }

    public function cancelOrder(Order $order, ?string $reason = null): Order
    {
        if ($order->status !== 'open') {
            throw new \RuntimeException('Only open (unpaid) orders can be cancelled.');
        }

        $order->status = 'cancelled';
        $order->cancel_reason = $reason;
        $order->cancelled_at = now();
        $order->save();

        if ($order->dining_table_id) {
            DiningTable::where('id', $order->dining_table_id)->update(['status' => 'available']);
        }

        return $order->fresh();
    }

    public function refundOrder(Order $order, float $amount, ?string $reason = null): Order
    {
        if ($order->status !== 'completed') {
            throw new \RuntimeException('Only completed (paid) orders can be refunded.');
        }

        if ($amount <= 0 || $amount > (float) $order->total) {
            throw new \RuntimeException('Refund amount must be between 0 and the order total.');
        }

        $order->status = 'refunded';
        $order->refund_amount = $amount;
        $order->refund_reason = $reason;
        $order->refunded_at = now();
        $order->save();

        return $order->fresh();
    }

    public function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $count = Order::whereDate('created_at', now()->toDateString())->count() + 1;
        return "ORD-{$date}-".str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}