@extends('layouts.user.app')

@section('title', 'Beranda')

@section('content')
<!-- Banner -->
<div class="relative w-full h-64 md:h-80 overflow-hidden rounded-xl shadow-md mb-8">
    <img src="{{ asset('storage/asset/book.jpg') }}" 
         alt="Banner Toko Buku" 
         class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
        <h1 class="text-white text-3xl md:text-5xl font-bold">Selamat Datang di Toko Buku Kami 📚</h1>
    </div>
</div>

<!-- Tentang Toko -->
<div class="text-center mx-auto mb-12">
    <h1 class="text-2xl font-semibold mb-3">Toko Buku Lengkap & Terpercaya</h1>
    <h2 class="text-gray-600 leading-relaxed">
        <bold> "Selamat datang di Book SHOP, surga bagi para pecinta buku! Temukan koleksi lengkap dari ribuan judul buku, mulai dari fiksi, non-fiksi, buku anak, hingga buku langka.
        Di sini, Anda dapat menjelajahi dunia literasi dari berbagai genre dan penulis terkemuka.
        Jual beli buku baru dan bekas dengan mudah, aman, dan harga terbaik. Mari temukan kisah favorit Anda dan perluas wawasan bersama kami!"Temukan berbagai koleksi buku menarik — dari pelajaran sekolah, novel populer, hingga buku pengembangan diri. 
        Kami menyediakan buku-buku berkualitas dengan harga terbaik, siap dikirim ke seluruh Indonesia.</bold>
    </h2>
</div>

<!-- Produk -->
<div class="max-w-6xl mx-auto px-4">
    <h2 class="text-2xl font-semibold mb-6 text-center">Produk Kami</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <img src="{{ asset('storage/' . $product->image) }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-48 object-cover">
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-2">{{ $product->name }}</h3>
                <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $product->description }}</p>
                <p class="text-blue-600 font-bold mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <a href="{{ route('user.products.show', $product->id) }}" 
                   class="inline-block bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 text-sm">
                   Lihat Detail
                </a>
            </div>
        </div>
        @empty
        <p class="col-span-full text-center text-gray-500">Belum ada produk yang tersedia.</p>
        @endforelse
    </div>
</div>
<!-- Simple Contact Section -->
<section class="contact-simple py-5 bg-dark text-white mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h3 class="mb-4">Contact Us</h3>
                <p class="lead mb-4">
                    Untuk informasi dan pemesanan, hubungi kami melalui:
                </p>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <i class="fas fa-phone fa-lg mb-2"></i>
                        <p>+62 812-3456-7890</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <i class="fas fa-envelope fa-lg mb-2"></i>
                        <p>bookshop@gmail.com</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <i class="fas fa-map-marker-alt fa-lg mb-2"></i>
                        <p>Jakarta, Indonesia</p>
                    </div>
                </div>
                <p class="text-muted">
                    Customer service kami siap membantu Anda dari Senin - Jumat, 09:00 - 18:00 WIB
                </p>
            </div>
        </div>
    </div>
</section>

<style>
.contact-simple {
    background: #2c3e50 !important;
}
</style>
@endsection
