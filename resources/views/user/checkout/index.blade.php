@extends('layouts.user.app')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        {{-- Flash Message --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('user.checkout.store') }}" method="POST">
            @csrf
            <div class="flex flex-col lg:flex-row gap-8">
                {{-- Left Column - Shipping Information --}}
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Informasi Pengiriman</h2>
                        
                        <div class="space-y-4">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" required 
                                       value="{{ auth()->user()->name }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Masukkan nama lengkap">
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" required
                                       value="{{ auth()->user()->email }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="nama@email.com">
                            </div>

                            {{-- Nomor Telepon --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nomor Telepon <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone" required
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="08xxxxxxxxxx">
                            </div>

                            {{-- Alamat Lengkap --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Alamat Lengkap <span class="text-red-500">*</span>
                                </label>
                                <textarea name="address" required rows="3"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Jalan, No. Rumah, RT/RW"></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Kota --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Kota <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="city" required
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="Nama kota">
                                </div>

                                {{-- Kode Pos --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Kode Pos <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="postal_code" required
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="12345">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Metode Pembayaran</h2>
                        
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:border-blue-500 cursor-pointer">
                                <input type="radio" name="payment_method" value="transfer" required class="mr-3 text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="font-semibold text-gray-900">Transfer Bank</span>
                                    <p class="text-sm text-gray-600 mt-1">Transfer ke rekening BCA, Mandiri, atau BNI</p>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:border-blue-500 cursor-pointer">
                                <input type="radio" name="payment_method" value="ewallet" class="mr-3 text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="font-semibold text-gray-900">E-Wallet</span>
                                    <p class="text-sm text-gray-600 mt-1">GoPay, OVO, DANA, atau LinkAja</p>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:border-blue-500 cursor-pointer">
                                <input type="radio" name="payment_method" value="cod" class="mr-3 text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="font-semibold text-gray-900">Bayar di Tempat (COD)</span>
                                    <p class="text-sm text-gray-600 mt-1">Bayar ketika pesanan sampai</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Right Column - Order Summary --}}
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Pesanan</h2>
                        
                        {{-- Order Items --}}
                        <div class="space-y-3 mb-4">
                            @php
                                $subtotal = 0;
                            @endphp
                            @foreach($cart as $id => $item)
                                @php
                                    $itemTotal = $item['price'] * $item['quantity'];
                                    $subtotal += $itemTotal;
                                @endphp
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 text-sm">{{ $item['name'] }}</p>
                                        <p class="text-xs text-gray-600">Qty: {{ $item['quantity'] }}</p>
                                    </div>
                                    <span class="font-semibold text-gray-900">Rp {{ number_format($itemTotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-4">

                        {{-- Calculation --}}
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ongkos Kirim</span>
                                <span class="text-green-600 font-medium">Gratis</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Pajak (11%)</span>
                                <span class="text-gray-900">Rp {{ number_format($subtotal * 0.11, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Total --}}
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-blue-600">
                                Rp {{ number_format($subtotal + ($subtotal * 0.11), 0, ',', '.') }}
                            </span>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 px-6 rounded-lg font-bold text-lg hover:from-blue-700 hover:to-purple-700 transition duration-200 shadow-lg">
                            Buat Pesanan
                        </button>

                        <p class="text-center text-gray-500 text-xs mt-3">
                            Dengan membuat pesanan, Anda menyetujui syarat dan ketentuan kami
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.bg-gradient-to-r {
    background: linear-gradient(45deg, #667eea, #764ba2);
}

.shadow-lg {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.sticky {
    position: sticky;
}

input[type="radio"]:checked + div {
    border-color: #3b82f6;
    background-color: #eff6ff;
}
</style>
@endsection