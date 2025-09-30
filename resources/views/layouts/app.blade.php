<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>{{ config('app.name') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="p-6">
  <nav class="mb-4 flex gap-4">
    <a href="{{ route('products.index') }}">Products</a>
    <a href="{{ route('cart.index') }}">Cart</a>
    @auth
      <span>Hi, {{ auth()->user()->name }}</span>
      <form method="POST" action="{{ route('logout') }}">@csrf<button>Logout</button></form>
    @else
      <a href="{{ route('login') }}">Login</a>
    @endauth
  </nav>
  @if(session('ok')) <div style="color:green">{{ session('ok') }}</div> @endif
  {{ $slot ?? '' }}
  @yield('content')
</body>
</html>
