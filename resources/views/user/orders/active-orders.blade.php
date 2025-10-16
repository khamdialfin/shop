@extends('layouts.user.app')

@section('title', 'Pesanan Aktif')

@section('content')
<div class="px-8 py-6 min-h-screen bg-gray-50">
    <h2 class="text-3xl font-semibold mb-6 flex items-center gap-2">
         Pesanan Aktif
    </h2>

    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
            @php
                // Ambil produk pertama dari pesanan (untuk preview di list)
                $firstItem = $order->items->first();
                $product = $firstItem ? $firstItem->product : null;

                // Tentukan path gambar: pakai yang di database, kalau kosong fallback ke default
                $imagePath = $product && $product->image 
                    ? asset('storage/' . $product->image) 
                    : asset('images/default.jpg');
            @endphp

            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition p-4 md:p-6 w-full flex flex-col md:flex-row justify-between items-center border border-gray-200">
                
                {{-- Bagian kiri: Produk --}}
                <div class="flex items-center gap-4 w-full md:w-1/2">
                    <img src="{{ $imagePath }}" 
                        alt="{{ $product->name ?? 'Produk' }}" 
                        class="w-24 h-28 object-cover rounded-lg shadow-sm border border-gray-200">
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $product->name ?? 'Produk Tidak Diketahui' }}</h3>
                        <p class="text-gray-600 text-sm">
                            Total: <span class="font-medium text-gray-800">
                                Rp{{ number_format($order->total_price, 0, ',', '.') }}
                            </span>
                        </p>

                        <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full 
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-700 
                            @elseif($order->status == 'completed') bg-green-100 text-green-700 
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                {{-- Bagian kanan: Info pesanan --}}
                <div class="w-full md:w-1/2 flex flex-col md:flex-row justify-end items-center gap-4 mt-4 md:mt-0">
                    <div class="text-sm text-gray-600">
                        <p><strong>ID Pesanan:</strong> #{{ $order->id }}</p>
                        <p><strong>Pembayaran:</strong> 
                            <span class="{{ $order->payment_status == 'unpaid' ? 'text-red-600 font-semibold' : 'text-green-600 font-semibold' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </p>
                        <p><strong>Metode:</strong> {{ $order->payment_method ?? '-' }}</p>
                    </div>

                    <a href="{{ route('user.active-orders.show', $order->id) }}" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600 mt-4">Belum ada pesanan aktif.</p>
    @endif
</div>
@endsection
