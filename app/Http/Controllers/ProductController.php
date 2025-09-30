<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        return view('products.index', ['products' => Product::latest()->get()]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'nullable'
        ]);
        Product::create($data);
        return back()->with('ok', 'Product created');
    }

    public function destroy(Product $product) {
        $product->delete();
        return back()->with('ok', 'Product deleted');
    }
}
