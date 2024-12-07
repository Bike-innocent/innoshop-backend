<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderLine;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    // Get all customer orders
    public function index()
    {
        $orders = CustomerOrder::with('customerOrderLines.product', 'user')->get();
        return response()->json($orders);
    }

    // Create a new order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'order_date' => 'required|date',
            'status' => 'required|in:pending,shipped,delivered,cancelled',
            'order_lines' => 'required|array|min:1',
            'order_lines.*.product_id' => 'required|exists:products,id',
            'order_lines.*.quantity' => 'required|integer|min:1',
            'order_lines.*.price' => 'required|numeric|min:0',
        ]);

        // Create order
        $order = CustomerOrder::create([
            'user_id' => $validated['user_id'],
            'order_date' => $validated['order_date'],
            'total_amount' => 0,
            'status' => $validated['status'],
        ]);

        $totalAmount = 0;

        // Create order lines
        foreach ($validated['order_lines'] as $line) {
            $order->customerOrderLines()->create([
                'product_id' => $line['product_id'],
                'quantity' => $line['quantity'],
                'price' => $line['price'],
            ]);
            $totalAmount += $line['quantity'] * $line['price'];
        }

        // Update total amount
        $order->update(['total_amount' => $totalAmount]);

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order->load('customerOrderLines.product'),
        ]);
    }

    // View a single order
    public function show($id)
    {
        $order = CustomerOrder::with('customerOrderLines.product', 'user')->findOrFail($id);
        return response()->json($order);
    }

    // Update an order and its order lines
    public function update(Request $request, $id)
    {
        $order = CustomerOrder::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,shipped,delivered,cancelled',
            'order_lines' => 'sometimes|array|min:1',
            'order_lines.*.id' => 'nullable|exists:customer_order_lines,id',
            'order_lines.*.product_id' => 'required_with:order_lines.*|exists:products,id',
            'order_lines.*.quantity' => 'required_with:order_lines.*|integer|min:1',
            'order_lines.*.price' => 'required_with:order_lines.*|numeric|min:0',
        ]);

        $order->update(['status' => $validated['status']]);

        $totalAmount = 0;

        // Update or create order lines
        if ($request->has('order_lines')) {
            foreach ($validated['order_lines'] as $line) {
                if (isset($line['id'])) {
                    // Update existing order line
                    $orderLine = CustomerOrderLine::find($line['id']);
                    $orderLine->update([
                        'product_id' => $line['product_id'],
                        'quantity' => $line['quantity'],
                        'price' => $line['price'],
                    ]);
                } else {
                    // Create new order line
                    $order->customerOrderLines()->create([
                        'product_id' => $line['product_id'],
                        'quantity' => $line['quantity'],
                        'price' => $line['price'],
                    ]);
                }
                $totalAmount += $line['quantity'] * $line['price'];
            }

            // Update total amount
            $order->update(['total_amount' => $totalAmount]);
        }

        return response()->json([
            'message' => 'Order updated successfully',
            'order' => $order->load('customerOrderLines.product'),
        ]);
    }

    // Soft delete an order
    public function destroy($id)
    {
        $order = CustomerOrder::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }

    // Restore a soft-deleted order
    public function restore($id)
    {
        $order = CustomerOrder::withTrashed()->findOrFail($id);
        $order->restore();

        return response()->json(['message' => 'Order restored successfully']);
    }
}
