@extends('layouts.user.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-8 mt-8">
    <h2 class="text-2xl font-semibold text-center mb-6 text-gray-800 border-b pb-3">
        🛒 Form Checkout
    </h2>

    <form action="{{ route('user.checkout.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Alamat -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman</label>
            <textarea name="address" required rows="3"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2"
                placeholder="Masukkan alamat lengkap kamu..."></textarea>
        </div>

        <!-- Metode Pembayaran -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
            <select name="payment_method" required
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                <option value="">-- Pilih Metode Pembayaran --</option>
                <option value="transfer">Transfer Bank</option>
                <option value="cod">COD (Bayar di Tempat)</option>
            </select>
        </div>

        <!-- Rincian Produk -->
        <div class="border-t pt-4">
            <h3 class="text-lg font-semibold mb-3 text-gray-800">🧾 Rincian Pesanan</h3>

            <div class="divide-y">
                @foreach($cart as $id => $item)
                <div class="flex items-center justify-between py-3">
                    <div>
                        <p class="font-medium text-gray-700">{{ $item['name'] }}</p>
                        <p class="text-sm text-gray-500">x {{ $item['quantity'] }}</p>
                    </div>
                    <div class="text-right text-gray-700 font-semibold">
                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex justify-between mt-4 text-lg font-semibold text-gray-800">
                <span>Total</span>
                <span>
                    Rp {{
                        number_format(
                            collect($cart)->sum(function ($item) {
                                return $item['price'] * $item['quantity'];
                            }),
                        0, ',', '.')
                    }}
                </span>
            </div>
        </div>

        <!-- Tombol Checkout -->
        <div class="text-center mt-6">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200 shadow">
                Checkout Sekarang
            </button>
        </div>
    </form>
</div>
@endsection
