@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
  <!-- Header -->
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold tracking-tight text-black">Add New Product</h1>
      <p class="mt-1 text-sm text-brand-gray">Create a new product in your catalog</p>
    </div>
    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
      Back to Products
    </a>
  </div>

  <!-- Form Card -->
  <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
      @csrf

      <!-- Product Image -->
      <div>
        <label class="block text-sm font-medium text-black mb-2">Product Image</label>
        <div class="flex items-center gap-4">
          <div id="preview" class="flex-shrink-0 h-32 w-32 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div class="flex-1">
            <input name="image" type="file" accept="image/*" id="imageInput" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-brand-red file:text-white hover:file:brightness-95">
            <p class="mt-1 text-xs text-gray-500">PNG, JPG, WEBP up to 2MB</p>
          </div>
        </div>
      </div>

      <!-- Product Details Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-black mb-2">Product Name *</label>
          <input name="name" value="{{ old('name') }}" placeholder="e.g., iPhone 15 Pro" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" required>
          @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-black mb-2">Price (€) *</label>
          <input name="price" value="{{ old('price') }}" placeholder="0.00" type="number" step="0.01" min="0" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" required>
          @error('price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-black mb-2">Category</label>
          <input name="category" value="{{ old('category') }}" placeholder="Electronics" list="categories" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red">
          <datalist id="categories">
            @foreach($categories as $cat)
              <option value="{{ $cat }}">
            @endforeach
          </datalist>
          @error('category')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-black mb-2">Stock Quantity</label>
          <input name="stock" value="{{ old('stock', 0) }}" placeholder="0" type="number" min="0" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red">
          @error('stock')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
      </div>

      <!-- Description -->
      <div>
        <label class="block text-sm font-medium text-black mb-2">Description</label>
        <textarea name="description" rows="4" placeholder="Brief product description" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red">{{ old('description') }}</textarea>
        @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
      </div>

      <!-- Actions -->
      <div class="flex items-center justify-end gap-3 pt-4 border-t">
        <a href="{{ route('products.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50">
          Cancel
        </a>
        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-red text-black px-6 py-2 font-medium hover:brightness-95 transition-all">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          Create Product
        </button>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('preview').innerHTML = '<img src="' + e.target.result + '" class="h-full w-full object-cover">';
    }
    reader.readAsDataURL(file);
  }
});
</script>
@endsection
