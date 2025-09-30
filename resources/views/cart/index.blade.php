@extends('layouts.app')

@section('content')
<h1>Your Cart</h1>
<ul>
@foreach($items as $i)
  <li>{{ $i->product->name }} x {{ $i->quantity }} = €{{ number_format($i->product->price * $i->quantity, 2) }}</li>
@endforeach
</ul>
<p><strong>Total:</strong> €{{ number_format($total, 2) }}</p>
<form method="POST" action="{{ route('checkout') }}">@csrf <button>Checkout</button></form>
@endsection
