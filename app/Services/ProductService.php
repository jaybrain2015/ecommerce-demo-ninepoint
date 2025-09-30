<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ProductService
{
    public function latest(int $limit = 10)
    {
        return Product::latest()->take($limit)->get();
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function importFake()
    {
        $res = Http::get('https://fakestoreapi.com/products');
        foreach ($res->json() as $p) {
            Product::updateOrCreate(
                ['name' => $p['title']],
                ['price' => $p['price'], 'description' => $p['description']]
            );
        }
    }

    public function delete(Product $product)
    {
        return $product->delete();
    }
}
