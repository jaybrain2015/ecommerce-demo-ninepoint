<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: localStorage.theme === 'dark', toggle(){ this.dark = !this.dark; localStorage.theme = this.dark ? 'dark' : 'light' } }" x-init="document.documentElement.classList.toggle('dark', dark)">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ninepoint_ecommerce_demo') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css','resources/js/app.js'])
  </head>
  <body class="min-h-screen bg-white text-gray-900 dark:bg-neutral-950 dark:text-neutral-100 flex flex-col">
    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 backdrop-blur supports-[backdrop-filter]:bg-white/70 bg-white/90 dark:bg-neutral-950/80 border-b border-gray-200/70 dark:border-neutral-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-3 md:py-4 grid grid-cols-12 items-center gap-y-2">
          <!-- Brand -->
          <div class="col-span-6 md:col-span-5 flex items-center gap-3">
            <a href="{{ url('/') }}" class="flex items-center gap-3 font-semibold text-lg text-black">
              <img src="/images/logo-ninepoint.svg" alt="ninepoint" class="h-8 w-auto mt-6" />
            </a>
          </div>

          <!-- Nav -->
          <nav class="col-span-12 md:col-span-6 flex items-center justify-center gap-6 text-sm">
              @if (Route::has('products.index'))
                <a href="{{ route('products.index') }}" class="text-black hover:text-brand-red px-2">Products</a>
              @else
                <a href="/products" class="text-black hover:text-brand-red px-2">Products</a>
              @endif
              @if (Route::has('cart.index'))
                <a href="{{ route('cart.index') }}" class="text-black hover:text-brand-red px-2">Cart</a>
              @else
                <a href="/cart" class="text-black hover:text-brand-red px-2">Cart</a>
              @endif
          </nav>

          <!-- Actions -->
          <div class="col-span-6 md:col-span-3 flex items-center gap-3 justify-end shrink-0 whitespace-nowrap">
            <!-- Dark mode toggle -->
            <button @click="toggle(); document.documentElement.classList.toggle('dark', dark)" type="button" class="inline-flex items-center justify-center w-9 h-9 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-100 dark:hover:bg-neutral-900" aria-label="Theme">
              <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z"/><path d="M12 2.25v1.5M12 20.25v1.5M4.81 4.81l1.06 1.06M18.13 18.13l1.06 1.06M1.5 12h1.5M20.25 12h1.5M4.81 19.19l1.06-1.06M18.13 5.87l1.06-1.06" stroke="currentColor" stroke-width="1.5"/></svg>
              <svg x-show="dark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M21.752 15.002A9 9 0 1 1 12.998 2.248a8 8 0 0 0 8.754 12.754Z"/></svg>
            </button>

            @auth
              <div class="flex items-center gap-3">
                <span class="text-sm text-black">Hi, {{ auth()->user()->name }}</span>
                @if (Route::has('logout'))
                <form method="POST" action="{{ route('logout') }}" class="inline">
                  @csrf
                  <button class="px-3 h-9 rounded-md text-sm bg-brand-red text-black hover:brightness-95">Logout</button>
                </form>
                @endif
              </div>
            @else
              @if (Route::has('login'))
                <a href="{{ route('login') }}" class="px-3 h-9 inline-flex items-center rounded-md text-sm border border-gray-200 hover:bg-gray-100">Login</a>
              @endif
              @if (Route::has('register'))
                <a href="{{ route('register') }}" class="px-3 h-9 inline-flex items-center rounded-md text-sm bg-brand-red text-white hover:brightness-95">Registrieren</a>
              @endif
            @endauth
          </div>
        </div>
      </div>
    </header>

    <!-- tis is for Flash messages -->
    @if (session('ok'))
      <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
        <div class="rounded-md border border-brand-red/30 bg-brand-red/10 text-brand-red px-4 py-3 text-sm">{{ session('ok') }}</div>
      </div>
    @endif

    <!-- Main content area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-1 w-full">
      {{ $slot ?? '' }}
      @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-6 border-t border-gray-200 dark:border-neutral-800 py-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-gray-500 dark:text-neutral-400">
        <div class="flex flex-col md:flex-row items-center justify-between gap-3">
          <p>&copy; {{ date('Y') }} ninepoint_ecommerce_demo</p>
          <p class="opacity-80">Built by john jonah</p>
        </div>
      </div>
    </footer>
  </body>
</html>
