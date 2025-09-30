@extends('layouts.app')

@section('content')
<div class="space-y-6">
  <!-- Header with Search & Filters -->
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold tracking-tight text-black">Products</h1>
      <p class="mt-1 text-sm text-brand-gray">Manage your product catalog</p>
    </div>
    <div class="flex items-center gap-3">
      <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-red text-black px-4 py-2 text-sm font-medium hover:brightness-95 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add Product
      </a>
    </div>
  </div>

  <!-- Search & Filters -->
  <div class="rounded-xl border border-gray-200 bg-gradient-to-r from-white to-gray-50 shadow-sm p-4">
    <form method="GET" class="flex items-center gap-3">
      <div class="relative">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-64 rounded-md border-gray-300 pl-10 text-sm focus:border-brand-red focus:ring-brand-red">
        <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
      </div>
      @if(isset($categories) && $categories->count())
      <select name="category" class="rounded-md border-gray-300 text-sm focus:border-brand-red focus:ring-brand-red" onchange="this.form.submit()">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
          <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
        @endforeach
      </select>
      @endif
      <button type="submit" class="px-4 py-2 bg-brand-red text-black rounded-md text-sm font-medium hover:brightness-95">Search</button>
    </form>
  </div>

  <!-- Products Table -->
  <div class="rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
          <tr>
            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Product</th>
            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Price</th>
            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden lg:table-cell">Category</th>
            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Stock</th>
            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Update Stock</th>
            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-32">Quantity</th>
            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        @forelse($products as $p)
        <tr class="hover:bg-gray-50 transition-colors">
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center gap-3">
              <div class="flex-shrink-0 h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                @if($p->image)
                  <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}" class="h-full w-full object-cover">
                @else
                  <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                @endif
              </div>
              <div>
                <div class="text-sm font-semibold text-black">{{ $p->name }}</div>
                <div class="text-xs text-gray-500">{{ $p->description ? Str::limit($p->description, 30) : 'ID: #' . $p->id }}</div>
              </div>
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-bold text-black">€{{ number_format($p->price, 2) }}</div>
          </td>
          <td class="px-6 py-4 hidden lg:table-cell">
            @if($p->category)
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $p->category }}</span>
            @else
              <span class="text-sm text-gray-400">—</span>
            @endif
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-center">
            @if($p->stock > 0)
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $p->stock > 10 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ $p->stock }}</span>
            @else
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">0</span>
            @endif
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <form method="POST" action="{{ route('products.updateStock', $p) }}" class="flex items-center justify-center gap-2">
              @csrf
              <input type="number" name="stock" value="{{ $p->stock }}" min="0" class="w-20 rounded-lg border-gray-300 text-center text-sm focus:border-brand-red focus:ring-brand-red">
              <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-brand-red text-brand-red px-2 py-1 text-xs font-medium hover:bg-brand-red hover:text-black transition-all">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
              </button>
            </form>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center justify-center">
              <input type="number" name="qty" form="add-{{ $p->id }}" value="1" min="1" max="{{ $p->stock > 0 ? $p->stock : 99 }}" step="1"
                     class="w-20 rounded-lg border-gray-300 text-center text-sm focus:border-brand-red focus:ring-brand-red" {{ $p->stock == 0 ? 'disabled' : '' }} />
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-right">
            <div class="flex items-center justify-end gap-2">
              <a href="{{ route('products.edit', $p) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 text-gray-700 px-3 py-2 text-sm font-medium hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit
              </a>
              <form id="add-{{ $p->id }}" method="POST" action="{{ route('cart.add', $p) }}">
                @csrf
                <button type="submit" {{ $p->stock == 0 ? 'disabled' : '' }} class="inline-flex items-center gap-1.5 rounded-lg border border-brand-red text-brand-red px-3 py-2 text-sm font-medium hover:bg-brand-red hover:text-black transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                  {{ $p->stock == 0 ? 'Out of Stock' : 'Add' }}
                </button>
              </form>
              <form method="POST" action="{{ route('products.destroy', $p) }}" onsubmit="return confirm('Delete {{ $p->name }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 text-gray-700 px-3 py-2 text-sm font-medium hover:bg-red-50 hover:border-red-300 hover:text-red-600 transition-all">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                  Delete
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="px-6 py-12">
            <div class="text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No products</h3>
              <p class="mt-1 text-sm text-gray-500">Get started by adding your first product above.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
    </div>
    
    <!-- Table Footer with Stats & Pagination -->
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-6 text-sm">
          <div class="flex items-center gap-2">
            <span class="text-gray-600">Total Products:</span>
            <span class="font-semibold text-black">{{ $products->total() }}</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-gray-600">In Stock:</span>
            <span class="font-semibold text-green-600">{{ $products->where('stock', '>', 0)->count() }}</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-gray-600">Catalog Value:</span>
            <span class="font-semibold text-black">€{{ number_format($products->sum('price'), 2) }}</span>
          </div>
        </div>
        <div>
          {{ $products->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
