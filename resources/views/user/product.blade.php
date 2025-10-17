@extends('layouts.user.app')

@section('title', 'Produk')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold mb-4">Daftar Produk</h2>
    
    {{-- Form Pencarian --}}
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form method="GET" action="{{ route('user.products.index') }}" class="flex flex-col md:flex-row gap-4">
            {{-- Search Input --}}
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       placeholder="Cari produk..." 
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            {{-- Category Filter --}}
            <div class="md:w-64">
                <select name="category" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- Buttons --}}
            <div class="flex gap-2">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="{{ route('user.products.index') }}" 
                   class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition flex items-center gap-2">
                    <i class="fas fa-refresh"></i> Reset
                </a>
            </div>
        </form>
        
        {{-- Hasil Pencarian Info --}}
        @if(request('search') || request('category'))
            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-800">
                    Menampilkan hasil 
                    @if(request('search')) untuk "<strong>{{ request('search') }}</strong>" @endif
                    @if(request('category')) 
                        @php
                            $selectedCategory = $categories->firstWhere('id', request('category'));
                        @endphp
                        @if($selectedCategory) dalam kategori <strong>{{ $selectedCategory->nama_kategori }}</strong> @endif
                    @endif
                    <span class="text-blue-600">({{ $products->total() }} hasil ditemukan)</span>
                </p>
            </div>
        @endif
    </div>
</div>

@if($products->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div class="bg-white shadow rounded-lg p-4 flex flex-col hover:shadow-lg transition-shadow duration-300">
            @if($product->image)
                <a href="{{ route('user.products.show', $product->id) }}">
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="h-48 w-full object-cover mb-4 rounded">
                </a>
            @else
                <div class="h-48 w-full bg-gray-200 mb-4 rounded flex items-center justify-center text-gray-500">
                    <i class="fas fa-book text-4xl"></i>
                </div>
            @endif
            
            <div class="flex-grow">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
                <p class="text-gray-600 text-sm mb-2">{{ $product->kategori->nama_kategori ?? 'Tidak ada kategori' }}</p>
                <p class="text-xl font-bold text-blue-600 mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                
                {{-- Stock Info --}}
                <div class="mb-4">
                    @if($product->stock > 0)
                        <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                            <i class="fas fa-check mr-1"></i> Stok: {{ $product->stock }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">
                            <i class="fas fa-times mr-1"></i> Stok Habis
                        </span>
                    @endif
                </div>
            </div>
            
            <a href="{{ route('user.products.show', $product->id) }}" 
               class="w-full px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-center flex items-center justify-center gap-2">
                <i class="fas fa-eye"></i> Detail
            </a>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $products->appends(request()->query())->links() }}
    </div>

@else
    {{-- Empty State --}}
    <div class="text-center py-12 bg-white rounded-lg shadow">
        <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Produk tidak ditemukan</h3>
        <p class="text-gray-500 mb-6">
            @if(request('search') || request('category'))
                Tidak ada produk yang sesuai dengan kriteria pencarian Anda.
            @else
                Belum ada produk tersedia.
            @endif
        </p>
        <a href="{{ route('user.products.index') }}" 
           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-refresh mr-2"></i> Lihat Semua Produk
        </a>
    </div>
@endif
@endsection