<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $products = $this->service->latest();
        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $this->service->create($validated);
        return back()->with('ok', 'Product created');
    }

    public function importFake()
    {
        $this->service->importFake();
        return back()->with('ok', 'Imported fake products');
    }

    public function destroy(Product $product)
    {
        $this->service->delete($product);
        return back()->with('ok', 'Product deleted');
    }
}
