<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Buku')</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
</head>
<body>
    {{-- Navbar User --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('user.home') }}">Toko Buku</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUser">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarUser">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a href="{{ route('user.home') }}" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="{{ route('user.products') }}" class="nav-link">Product</a></li>
                    <li class="nav-item"><a href="{{ route('user.about') }}" class="nav-link">About Us</a></li>
                    <li class="nav-item"><a href="{{ route('user.contact') }}" class="nav-link">Contact</a></li>
                    <li class="nav-item"><a href="{{ route('login') }}" class="btn btn-outline-primary ms-2">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <div class="container py-5">
        @yield('content')
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
