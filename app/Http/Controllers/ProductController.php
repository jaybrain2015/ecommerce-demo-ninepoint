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

    public function index(Request $request)
    {
        $query = Product::query();

        
        if ($search = $request->get('search')) {
            $query->search($search);
        }

        
        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        
        if ($request->has('active_only')) {
            $query->active();
        }

       
        $products = $query->latest()->paginate(15);
        $categories = Product::distinct()->pluck('category')->filter();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Product::distinct()->pluck('category')->filter();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

      
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $this->service->create($validated);
        return redirect()->route('products.index')->with('ok', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $categories = Product::distinct()->pluck('category')->filter();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

      
        if ($request->hasFile('image')) {
          
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);
        return redirect()->route('products.index')->with('ok', 'Product updated successfully');
    }

    public function importFake()
    {
        $this->service->importFake();
        return back()->with('ok', 'Imported fake products');
    }

    public function destroy(Product $product)
    {
       
        if ($product->image) {
            \Storage::disk('public')->delete($product->image);
        }
        $this->service->delete($product);
        return back()->with('ok', 'Product deleted');
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($validated);
        return back()->with('ok', 'Stock updated successfully');
    }
}
