<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutOrderRequest;
use App\Http\Requests\RefundOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Category;
use App\Models\DiningTable;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use App\Services\SettingService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
    }

    /**
     * Main billing / POS screen. Can be opened blank (takeaway / walk-in, manual add)
     * or with ?table=ID to load/continue an existing table's saved order.
     */
    public function pos(Request $request)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_available', true)->orderBy('name')->get();

        $table = null;
        $activeOrder = null;

        if ($request->filled('table')) {
            $table = DiningTable::findOrFail($request->get('table'));
            $activeOrder = Order::with('items.product')
                ->where('dining_table_id', $table->id)
                ->where('status', 'open')
                ->latest()
                ->first();
        }

        $tables = DiningTable::where('is_active', true)->orderBy('name')->get();
        $settings = app(SettingService::class)->all();

        return view('orders.pos', compact('categories', 'products', 'table', 'activeOrder', 'tables', 'settings'));
    }

    /**
     * Save (hold) the current cart against a table — or as a standalone open order
     * for takeaway — WITHOUT completing payment. This is the "save items for later
     * billing" feature for tables.
     */
    public function save(StoreOrderRequest $request)
    {
        $existing = null;
        if ($request->filled('order_id')) {
            $existing = Order::where('status', 'open')->findOrFail($request->order_id);
        } elseif ($request->filled('dining_table_id')) {
            $existing = Order::where('dining_table_id', $request->dining_table_id)
                ->where('status', 'open')->latest()->first();
        }

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $settings = app(SettingService::class)->all();
        $data['tax_percent'] = $data['tax_percent'] ?? $settings['tax_percent'];

        $order = $this->orderService->saveOrder($data, $existing);

        return response()->json([
            'success' => true,
            'order' => $order,
            'message' => 'Order saved.',
        ]);
    }

    /**
     * Complete payment for an order and generate the printable receipt.
     */
    public function checkout(CheckoutOrderRequest $request, Order $order)
    {
        $order = $this->orderService->checkout($order, $request->validated());

        return response()->json([
            'success' => true,
            'order' => $order,
            'receipt_url' => route('orders.receipt', $order),
        ]);
    }

    /**
     * Cancel an order that hasn't been paid yet (still "open"/held on a table).
     * Reason is optional — kept as a short, no-friction flow.
     */
    public function cancel(Request $request, Order $order)
    {
        $this->authorize('cancel', $order);

        $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $order = $this->orderService->cancelOrder($order, $request->reason);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true, 'order' => $order, 'message' => 'Order cancelled.']);
    }

    /**
     * Refund an already-paid order, in full or in part. Reduces what that
     * order counts toward sales/reports without touching the original bill.
     */
    public function refund(RefundOrderRequest $request, Order $order)
    {
        $this->authorize('refund', $order);

        try {
            $order = $this->orderService->refundOrder($order, (float) $request->refund_amount, $request->reason);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json([
            'success' => true,
            'order' => $order,
            'message' => 'Refund processed.',
            'receipt_url' => route('orders.receipt', $order),
        ]);
    }

    public function receipt(Order $order)
    {
        $order->load('items', 'diningTable', 'customer', 'cashier');
        $settings = app(SettingService::class)->all();
        return view('orders.receipt', compact('order', 'settings'));
    }

    public function history(Request $request)
    {
        $orders = Order::with(['diningTable', 'cashier', 'customer'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('q'), fn ($q) => $q->where('order_number', 'like', '%'.$request->q.'%'))
            ->when($request->filled('date'), fn ($q) => $q->whereDate('created_at', $request->date))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('orders.history', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items', 'diningTable', 'customer', 'cashier');
        return response()->json(['order' => $order]);
    }
}
