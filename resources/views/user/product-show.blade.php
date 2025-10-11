@extends('layouts.user.app')

@section('title', $product->name)

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Gambar Produk -->
        <div class="md:w-1/2">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="w-full object-cover rounded">
            @else
                <div class="h-64 w-full bg-gray-200 rounded flex items-center justify-center text-gray-500">No Image</div>
            @endif
        </div>

        <!-- Detail Produk -->
        <div class="md:w-1/2 flex flex-col">
            <h2 class="text-2xl font-bold mb-2">{{ $product->name }}</h2>
            <p class="text-gray-700 mb-4">{{ $product->description ?? 'Deskripsi belum tersedia.' }}</p>
            <p class="text-xl font-semibold mb-4">Rp {{ number_format($product->price,0,',','.') }}</p>
            <form action="{{ route('user.cart.add', $product->id) }}" method="POST">
            @csrf
                <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-800">Masukkan Keranjang</button>
            </form>
        </div>
    </div>
</div>
@endsection
