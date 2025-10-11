@extends('layouts.user.app')

@section('title', 'Keranjang Belanja')

@section('content')
<h2 class="text-2xl font-bold mb-4">Keranjang Belanja</h2>

{{-- Flash Message --}}
@if(session('success'))
    <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-200 text-red-800 p-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

@if($cart && count($cart) > 0)
<table class="w-full bg-white shadow rounded">
    <thead>
        <tr class="border-b">
            <th class="p-2 text-left">Produk</th>
            <th class="p-2 text-left">Harga</th>
            <th class="p-2 text-left">Jumlah</th>
            <th class="p-2 text-left">Subtotal</th>
            <th class="p-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @foreach($cart as $id => $details)
        @php $subtotal = $details['price'] * $details['quantity']; $total += $subtotal; @endphp
        <tr class="border-b">
            <td class="p-2 flex items-center gap-2">
                @if($details['image'])
                    <img src="{{ asset('storage/' . $details['image']) }}" class="h-16 w-16 object-cover rounded">
                @endif
                {{ $details['name'] }}
            </td>
            <td class="p-2">Rp {{ number_format($details['price'],0,',','.') }}</td>
            <td class="p-2">
                <form action="{{ route('user.cart.update', $id) }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="w-16 border rounded px-2 py-1">
                    <button type="submit" class="px-2 py-1 bg-blue-700 text-white rounded hover:bg-blue-800">Update</button>
                </form>
            </td>
            <td class="p-2">Rp {{ number_format($subtotal,0,',','.') }}</td>
            <td class="p-2">
                <form action="{{ route('user.cart.remove', $id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3" class="p-2 font-bold text-right">Total</td>
            <td class="p-2 font-bold">Rp {{ number_format($total,0,',','.') }}</td>
            <td></td>
        </tr>
    </tbody>
</table>

{{-- Tombol Checkout --}}
<form action="{{ route('user.checkout.index') }}" method="GET" class="mt-4">
    @csrf
    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Checkout</button>
</form>

@else
<p>Keranjang belanja kosong.</p>
@endif
@endsection
