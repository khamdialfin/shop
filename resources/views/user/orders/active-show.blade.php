@extends('layouts.user.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="min-h-[calc(100vh-120px)] flex flex-col">
    <div class="flex-grow max-w-5xl mx-auto bg-white shadow-lg rounded-xl p-8 mt-10 mb-10 border border-gray-100">
        <!-- Judul -->
        <div class="flex items-center justify-between border-b pb-4 mb-6">
            <h2 class="text-3xl font-bold text-gray-800">🧾 Detail Pesanan #{{ $order->id }}</h2>
            <span class="px-4 py-1 rounded-full text-sm 
                @if($order->status == 'pending') bg-yellow-100 text-yellow-800 
                @elseif($order->status == 'completed') bg-green-100 text-green-700 
                @elseif($order->status == 'cancelled') bg-red-100 text-red-700 
                @else bg-gray-100 text-gray-700 @endif">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <!-- Informasi Pesanan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700 mb-8">
            <p><strong>📍 Alamat:</strong> {{ $order->address }}</p>
            <p><strong>💳 Metode Pembayaran:</strong> {{ ucfirst($order->payment_method) }}</p>
            <p><strong>📦 Status Order:</strong> {{ ucfirst($order->status) }}</p>
            <p>
                <strong>💰 Status Pembayaran:</strong>
                <span class="@if($order->payment_status == 'paid') text-green-600 @else text-red-600 @endif">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </p>
        </div>

        <!-- Produk -->
        <h3 class="text-xl font-semibold text-gray-800 mb-3 border-b pb-2">🛍️ Produk Dipesan</h3>
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full text-sm text-gray-700 border rounded-lg overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-3 px-4 text-left font-semibold">Produk</th>
                        <th class="py-3 px-4 text-left font-semibold">Harga</th>
                        <th class="py-3 px-4 text-center font-semibold">Jumlah</th>
                        <th class="py-3 px-4 text-right font-semibold">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-3 px-4">{{ $item->product->name }}</td>
                        <td class="py-3 px-4">Rp {{ number_format($item->price,0,',','.') }}</td>
                        <td class="py-3 px-4 text-center">{{ $item->quantity }}</td>
                        <td class="py-3 px-4 text-right">Rp {{ number_format($item->price * $item->quantity,0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total dan Tombol Aksi -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <p class="text-lg font-semibold text-gray-800">
                💵 <strong>Total:</strong> Rp {{ number_format($order->total_price,0,',','.') }}
            </p>

            <div class="flex gap-3">
                @if($order->payment_status == 'unpaid')
                    <form action="{{ route('user.active-orders.pay', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                            class="px-5 py-2 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700 transition">
                            💳 Bayar Sekarang
                        </button>
                    </form>
                @endif

                @if($order->status == 'completed' && !$order->confirmed_by_user)
                    <form action="{{ route('user.active-orders.confirm', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                            class="px-5 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition">
                            ✅ Konfirmasi Diterima
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
