@extends('layouts.user.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Detail Pesanan #{{ $order->id }}</h2>

<div class="mb-4">
    <p><strong>Alamat:</strong> {{ $order->address }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
    <p><strong>Status Order:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Status Pembayaran:</strong> {{ ucfirst($order->payment_status) }}</p>
    <p><strong>Dikonfirmasi User:</strong> {{ $order->confirmed_by_user ? 'Ya' : 'Belum' }}</p>
</div>

<h3 class="text-xl font-semibold mb-2">Produk:</h3>
<table class="w-full border rounded mb-4">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-2">Produk</th>
            <th class="p-2">Harga</th>
            <th class="p-2">Jumlah</th>
            <th class="p-2">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr class="border-b">
            <td class="p-2">{{ $item->product->name }}</td>
            <td class="p-2">Rp {{ number_format($item->price,0,',','.') }}</td>
            <td class="p-2">{{ $item->quantity }}</td>
            <td class="p-2">Rp {{ number_format($item->price * $item->quantity,0,',','.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<p class="mb-4"><strong>Total:</strong> Rp {{ number_format($order->total_price,0,',','.') }}</p>

@endsection
