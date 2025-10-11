<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Buku')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0e9b5d4.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 font-sans flex flex-col min-h-screen">

    {{-- HEADER --}}
    <header class="bg-white shadow-md">
        <div class="container mx-auto grid grid-cols-3 items-center py-4 px-6">
            
            {{-- LOGO (KIRI) --}}
            <a href="{{ route('user.home') }}" class="text-2xl font-bold text-blue-700 hover:text-blue-800 transition flex items-center gap-2">
                📚 <span>Book Shop</span>
            </a>

            {{-- NAVBAR (TENGAH) --}}
            <nav class="flex justify-center items-center space-x-6 text-gray-700 font-medium">
                <a href="{{ route('user.home') }}" class="hover:text-blue-700 flex items-center gap-1">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="{{ route('user.products.index') }}" class="hover:text-blue-700 flex items-center gap-1">
                    <i class="fas fa-book"></i> Produk
                </a>
                <a href="{{ route('user.cart.index') }}" class="hover:text-blue-700 flex items-center gap-1">
                    <i class="fas fa-shopping-cart"></i> Keranjang
                </a>
                <a href="{{ route('user.active-orders.index') }}" class="hover:text-blue-700 flex items-center gap-1">
                    <i class="fas fa-truck"></i> Pesanan Aktif
                </a>
                <a href="{{ route('user.orders.index') }}" class="hover:text-blue-700 flex items-center gap-1">
                    <i class="fas fa-clock-rotate-left"></i> Riwayat
                </a>
            </nav>

            {{-- LOGOUT (KANAN) --}}
            <div class="flex justify-end">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition flex items-center gap-2 text-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="container mx-auto flex-grow mt-8 px-6">
        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-3 rounded mb-4 shadow">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    {{-- FOOTER (Sticky) --}}
    <footer class="bg-white shadow-inner mt-auto">
        <div class="container mx-auto text-center py-6 text-gray-600 text-sm">
            © 2025 <span class="font-semibold text-blue-700">Toko Buku</span>. Semua hak dilindungi.
        </div>
    </footer>

</body>
</html>
