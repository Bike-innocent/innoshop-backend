<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;

use App\Models\SupplierOrder;
use App\Models\SupplierOrderLine;
use Illuminate\Http\Request;

class SupplierOrderController extends Controller
{
    public function index()
    {
        $orders = SupplierOrder::with('orderLines.product')->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:users,id',
            'order_date' => 'required|date',
            'status' => 'required|in:pending,received,cancelled',
            'order_lines' => 'required|array',
            'order_lines.*.product_id' => 'required|exists:products,id',
            'order_lines.*.quantity' => 'required|integer|min:1',
            'order_lines.*.cost_price' => 'required|numeric|min:0',
        ]);

        $order = SupplierOrder::create([
            'supplier_id' => $validated['supplier_id'],
            'order_date' => $validated['order_date'],
            'status' => $validated['status'],
        ]);

        foreach ($validated['order_lines'] as $line) {
            SupplierOrderLine::create([
                'supplier_order_id' => $order->id,
                'product_id' => $line['product_id'],
                'quantity' => $line['quantity'],
                'cost_price' => $line['cost_price'],
            ]);
        }

        return response()->json(['message' => 'Supplier Order created successfully', 'order' => $order]);
    }

    public function show($id)
    {
        $order = SupplierOrder::with('orderLines.product')->findOrFail($id);
        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = SupplierOrder::findOrFail($id);
        $order->delete();
        return response()->json(['message' => 'Supplier Order deleted successfully']);
    }
}
