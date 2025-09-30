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

    public function add(Product $product) {
        Cart::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $product->id],
            ['quantity' => \DB::raw('quantity + 1')]
        );
        return back()->with('ok', 'Added to cart');
    }
}
