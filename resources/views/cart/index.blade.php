@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
  <!-- Header -->
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold tracking-tight text-black">Shopping Cart</h1>
      <p class="mt-1 text-sm text-brand-gray">{{ $items->count() }} {{ Str::plural('item', $items->count()) }} in your cart</p>
    </div>
    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
      Continue Shopping
    </a>
  </div>

  @if($items->count() > 0)
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Cart Items -->
    <div class="lg:col-span-2 space-y-4">
      @foreach($items as $item)
      <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-6">
          <div class="flex items-start gap-4">
            <!-- Product Image -->
            <div class="flex-shrink-0 h-24 w-24 bg-gray-100 rounded-lg overflow-hidden">
              @if($item->product->image)
                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
              @else
                <div class="h-full w-full flex items-center justify-center">
                  <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                </div>
              @endif
            </div>

            <!-- Product Details -->
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between">
                <div>
                  <h3 class="text-lg font-semibold text-black">{{ $item->product->name }}</h3>
                  @if($item->product->category)
                    <p class="mt-1 text-sm text-gray-500">{{ $item->product->category }}</p>
                  @endif
                  @if($item->product->description)
                    <p class="mt-2 text-sm text-gray-600">{{ Str::limit($item->product->description, 100) }}</p>
                  @endif
                </div>
                <p class="text-lg font-bold text-black ml-4">€{{ number_format($item->product->price, 2) }}</p>
              </div>

              <!-- Quantity Controls & Remove -->
              <div class="mt-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <span class="text-sm font-medium text-gray-700">Quantity:</span>
                  <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                    @csrf
                    <div class="flex items-center border border-gray-300 rounded-lg">
                      <button type="button" onclick="decrementQty({{ $item->id }})" class="px-3 py-1.5 hover:bg-gray-100 rounded-l-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                      </button>
                      <input type="number" name="quantity" id="qty-{{ $item->id }}" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="w-16 text-center border-x border-gray-300 py-1.5 focus:outline-none focus:ring-2 focus:ring-brand-red">
                      <button type="button" onclick="incrementQty({{ $item->id }}, {{ $item->product->stock }})" class="px-3 py-1.5 hover:bg-gray-100 rounded-r-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                      </button>
                    </div>
                    <button type="submit" class="px-3 py-1.5 text-sm font-medium text-brand-red border border-brand-red rounded-lg hover:bg-brand-red hover:text-white transition-all">
                      Update
                    </button>
                  </form>
                </div>

                <form method="POST" action="{{ route('cart.remove', $item) }}" onsubmit="return confirm('Remove this item from cart?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Remove
                  </button>
                </form>
              </div>

              <!-- Subtotal -->
              <div class="mt-3 pt-3 border-t border-gray-200">
                <div class="flex items-center justify-between text-sm">
                  <span class="text-gray-600">Subtotal:</span>
                  <span class="font-semibold text-black">€{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <!-- Order Summary -->
    <div class="lg:col-span-1">
      <div class="rounded-xl border border-gray-200 bg-white shadow-sm sticky top-24">
        <div class="p-6 space-y-4">
          <h2 class="text-xl font-bold text-black">Order Summary</h2>
          
          <div class="space-y-3 py-4 border-y border-gray-200">
            <div class="flex items-center justify-between text-sm">
              <span class="text-gray-600">Subtotal</span>
              <span class="font-medium text-black">€{{ number_format($total, 2) }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
              <span class="text-gray-600">Shipping</span>
              <span class="font-medium text-green-600">Free</span>
            </div>
            <div class="flex items-center justify-between text-sm">
              <span class="text-gray-600">Tax (estimated)</span>
              <span class="font-medium text-black">€{{ number_format($total * 0.19, 2) }}</span>
            </div>
          </div>

          <div class="flex items-center justify-between pt-2">
            <span class="text-lg font-bold text-black">Total</span>
            <span class="text-2xl font-bold text-black">€{{ number_format($total * 1.19, 2) }}</span>
          </div>

          <form method="POST" action="{{ route('checkout') }}" class="space-y-3">
            @csrf
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-brand-red text-black px-6 py-3 font-semibold hover:brightness-95 transition-all">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
              Proceed to Checkout
            </button>
          </form>

          <div class="pt-4 space-y-2 text-xs text-gray-500">
            <div class="flex items-start gap-2">
              <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              <span>Secure checkout</span>
            </div>
            <div class="flex items-start gap-2">
              <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              <span>Free shipping on all orders</span>
            </div>
            <div class="flex items-start gap-2">
              <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              <span>30-day return policy</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @else
  <!-- Empty Cart State -->
  <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="p-12 text-center">
      <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
      </svg>
      <h3 class="mt-4 text-xl font-semibold text-black">Your cart is empty</h3>
      <p class="mt-2 text-sm text-gray-500">Add some products to get started!</p>
      <a href="{{ route('products.index') }}" class="mt-6 inline-flex items-center gap-2 rounded-lg bg-brand-red text-black px-6 py-3 font-medium hover:brightness-95 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        Start Shopping
      </a>
    </div>
  </div>
  @endif
</div>

<script>
function incrementQty(itemId, max) {
  const input = document.getElementById('qty-' + itemId);
  if (parseInt(input.value) < max) {
    input.value = parseInt(input.value) + 1;
  }
}

function decrementQty(itemId) {
  const input = document.getElementById('qty-' + itemId);
  if (parseInt(input.value) > 1) {
    input.value = parseInt(input.value) - 1;
  }
}
</script>
@endsection
