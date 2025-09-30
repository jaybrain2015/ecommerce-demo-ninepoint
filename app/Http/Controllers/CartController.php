<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index() {
        $items = Cart::with('product')->where('user_id', auth()->id())->get();
        $total = $items->sum(fn($i) => $i->product->price * $i->quantity);
        return view('cart.index', compact('items','total'));
    }

    public function add(Request $request, Product $product) {
        $qty = max(1, (int) $request->input('qty', 1));

        $item = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->increment('quantity', $qty);
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $qty,
            ]);
        }

        return back()->with('ok', 'Added to cart');
    }

    public function updateQuantity(Request $request, Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->update($validated);
        return back()->with('ok', 'Cart updated');
    }

    public function remove(Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cart->delete();
        return back()->with('ok', 'Item removed from cart');
    }
}
