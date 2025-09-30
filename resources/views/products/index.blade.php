@extends('layouts.app')

@section('content')
<h1>Products</h1>

<form method="POST" action="{{ route('products.store') }}" class="mb-4">
  @csrf
  <input name="name" placeholder="Name">
  <input name="price" placeholder="Price" type="number" step="0.01">
  <input name="description" placeholder="Description">
  <button type="submit">Add</button>
</form>

<ul>
@foreach($products as $p)
  <li>
    <strong>{{ $p->name }}</strong> (€{{ $p->price }})
    <form method="POST" action="{{ route('cart.add', $p) }}" style="display:inline">
      @csrf <button>Add to Cart</button>
    </form>
    <form method="POST" action="{{ route('products.destroy', $p) }}" style="display:inline">
      @csrf @method('DELETE') <button>Delete</button>
    </form>
  </li>
@endforeach
</ul>
@endsection
