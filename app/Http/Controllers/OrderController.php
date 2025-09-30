<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;

class OrderController extends Controller
{
    public function checkout() {
        $items = Cart::with('product')->where('user_id', auth()->id())->get();
        $total = $items->sum(fn($i) => $i->product->price * $i->quantity);
        $order = Order::create(['user_id' => auth()->id(), 'total' => $total, 'status' => 'pending']);
        Cart::where('user_id', auth()->id())->delete();
        return back()->with('ok', "Order #{$order->id} placed (total €{$order->total})");
    }
}
