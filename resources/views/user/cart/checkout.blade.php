@extends('layouts.user.app')
@section('title', 'Checkout')
@section('content')

<h2 class="text-2xl font-bold mb-4">Checkout</h2>

@if($cart->count())
<form action="{{ route('user.checkout.store') }}" method="POST">
    @csrf

    <!-- Alamat Pengiriman -->
    <div class="mb-4 bg-white shadow rounded p-4">
        <h3 class="text-xl font-bold mb-2">Alamat Pengiriman</h3>
        <textarea name="address" class="w-full border rounded px-2 py-2" rows="3" required>{{ old('address') }}</textarea>
    </div>

    <!-- Metode Pembayaran -->
    <div class="mb-4 bg-white shadow rounded p-4">
        <h3 class="text-xl font-bold mb-2">Metode Pembayaran</h3>
        <select name="payment_method" class="w-full border rounded px-2 py-2" required>
            <option value="">-- Pilih Metode Pembayaran --</option>
            <option value="transfer_bank">Transfer Bank</option>
            <option value="cod">Cash on Delivery (COD)</option>
            <option value="ewallet">E-Wallet</option>
        </select>
    </div>

    <!-- Produk dalam Keranjang -->
    <div class="mb-4 bg-white shadow rounded p-4">
        <h3 class="text-xl font-bold mb-2">Produk yang Dibeli</h3>
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="p-2 text-left">Produk</th>
                    <th class="p-2 text-left">Harga</th>
                    <th class="p-2 text-left">Jumlah</th>
                    <th class="p-2 text-left">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($cart as $item)
                @php $subtotal = $item['price'] * $item['quantity']; @endphp
                <tr class="border-b">
                    <td class="p-2">{{ $item['name'] }}</td>
                    <td class="p-2">Rp {{ number_format($item['price'],0,',','.') }}</td>
                    <td class="p-2">{{ $item['quantity'] }}</td>
                    <td class="p-2">Rp {{ number_format($subtotal,0,',','.') }}</td>
                </tr>
                @php $total += $subtotal; @endphp
                @endforeach
            </tbody>
        </table>

        <p class="mt-2 font-bold text-right">Total: Rp {{ number_format($total,0,',','.') }}</p>
    </div>

    <!-- Submit Checkout -->
    <div class="mb-4">
        <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-800">Konfirmasi Checkout</button>
    </div>
</form>
@else
<p class="text-gray-500">Keranjang kosong. Tambahkan produk terlebih dahulu.</p>
@endif

@endsection
