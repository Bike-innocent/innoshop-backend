<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;

class CartController extends Controller
{

    public function index()
{
    $cart = CartItem::with('product')->where('user_id', auth()->id())->get();
    return response()->json($cart);
}

    public function sync(Request $request)
    {
        $user = auth()->user();
        $cart = $request->input('cart'); // Cart data from localStorage

        foreach ($cart as $item) {
            CartItem::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'product_id' => $item['id'],
                ],
                [
                    'quantity' => $item['quantity'],
                ]
            );
        }

        return response()->json(['message' => 'Cart synced successfully']);
    }
}
