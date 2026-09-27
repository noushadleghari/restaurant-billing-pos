<?php

namespace App\Services;

use App\Models\DiningTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Create a new order OR update the existing open order for a table (save/hold).
     * This is what powers "add items to a table and save for later billing".
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

            // Replace items (simplest, safest approach for "save cart for table")
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

            $discount = (float) ($data['discount'] ?? 0);
            $taxPercent = (float) ($data['tax_percent'] ?? 0);
            $taxable = max($subtotal - $discount, 0);
            $taxAmount = round($taxable * ($taxPercent / 100), 2);

            $order->subtotal = $subtotal;
            $order->discount = $discount;
            $order->tax_percent = $taxPercent;
            $order->tax_amount = $taxAmount;
            $order->total = round($taxable + $taxAmount, 2);
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
                $discount = (float) ($paymentData['discount'] ?? $order->discount);
                $taxPercent = (float) ($paymentData['tax_percent'] ?? $order->tax_percent);
                $taxable = max($order->subtotal - $discount, 0);
                $order->discount = $discount;
                $order->tax_percent = $taxPercent;
                $order->tax_amount = round($taxable * ($taxPercent / 100), 2);
                $order->total = round($taxable + $order->tax_amount, 2);
            }

            $order->payment_method = $paymentData['payment_method'];
            $order->paid_amount = $paymentData['paid_amount'];
            $order->change_amount = max($paymentData['paid_amount'] - $order->total, 0);
            $order->status = 'completed';
            $order->completed_at = now();
            $order->save();

            if ($order->dining_table_id) {
                DiningTable::where('id', $order->dining_table_id)->update(['status' => 'available']);
            }

            return $order->fresh('items', 'diningTable', 'customer', 'cashier');
        });
    }

    public function cancel(Order $order): void
    {
        $order->status = 'cancelled';
        $order->save();

        if ($order->dining_table_id) {
            DiningTable::where('id', $order->dining_table_id)->update(['status' => 'available']);
        }
    }

    public function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $count = Order::whereDate('created_at', now()->toDateString())->count() + 1;
        return "ORD-{$date}-".str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
