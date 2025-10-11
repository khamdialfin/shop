@extends('layouts.user.app')

@section('title', 'Produk')

@section('content')
<h2 class="text-2xl font-bold mb-4">Daftar Produk</h2>

@if($products->count())
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @foreach($products as $product)
    <div class="bg-white shadow rounded-lg p-4 flex flex-col">
        @if($product->image)
            <a href="{{ route('user.products.show', $product->id) }}">
                <img src="{{ asset('storage/' . $product->image) }}" class="h-48 w-full object-cover mb-4 rounded">
            </a>
        @else
            <div class="h-48 w-full bg-gray-200 mb-4 rounded flex items-center justify-center text-gray-500">No Image</div>
        @endif
        <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
        <p>Rp {{ number_format($product->price,0,',','.') }}</p>
        <a href="{{ route('user.products.show', $product->id) }}" class="mt-auto px-3 py-1 bg-blue-700 text-white rounded hover:bg-blue-800 text-center">Detail</a>
    </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $products->links() }} <!-- pagination -->
</div>

@else
<p>Belum ada produk tersedia.</p>
@endif
@endsection
