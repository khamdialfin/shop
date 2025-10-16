@extends('layouts.user.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Flash Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if($cart && count($cart) > 0)
    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Left Column - Cart Items --}}
        <div class="lg:w-2/3">
            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Keranjang Belanja</h1>
                <p class="text-gray-600 mt-2">{{ count($cart) }} item dalam keranjang</p>
            </div>

            {{-- Cart Items --}}
            <div class="space-y-4">
                @php $total = 0; @endphp
                @foreach($cart as $id => $details)
                @php 
                    $subtotal = $details['price'] * $details['quantity']; 
                    $total += $subtotal; 
                @endphp
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        {{-- Product Image --}}
                        <div class="flex-shrink-0">
                            @if($details['image'])
                                <img src="{{ asset('storage/' . $details['image']) }}" 
                                     alt="{{ $details['name'] }}" 
                                     class="h-24 w-24 object-cover rounded-lg">
                            @else
                                <div class="h-24 w-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-book text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Product Details --}}
                        <div class="flex-grow">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $details['name'] }}</h3>
                            <p class="text-gray-600 text-sm">oleh {{ $details['author'] ?? 'Penulis' }}</p>
                            <p class="text-blue-600 font-bold text-lg mt-2">Rp {{ number_format($details['price'],0,',','.') }}</p>
                        </div>

                        {{-- Quantity Controls --}}
                        <div class="flex items-center gap-3">
                            <form action="{{ route('user.cart.update', $id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <button type="submit" name="action" value="decrease" 
                                        class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full hover:bg-gray-100 {{ $details['quantity'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $details['quantity'] <= 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-minus text-sm"></i>
                                </button>
                                
                                <span class="w-12 text-center font-medium">{{ $details['quantity'] }}</span>
                                
                                <button type="submit" name="action" value="increase" 
                                        class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full hover:bg-gray-100">
                                    <i class="fas fa-plus text-sm"></i>
                                </button>
                            </form>
                        </div>

                        {{-- Subtotal & Remove --}}
                        <div class="text-right">
                            <p class="text-blue-600 font-bold text-lg mb-2">
                                Rp {{ number_format($subtotal,0,',','.') }}
                            </p>
                            <form action="{{ route('user.cart.remove', $id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Clear Cart Button --}}
            <div class="mt-6">
                <form action="{{ route('user.cart.clear') }}" method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 border border-red-600 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition duration-200">
                        <i class="fas fa-trash mr-2"></i>Kosongkan Keranjang
                    </button>
                </form>
            </div>
        </div>

        {{-- Right Column - Order Summary --}}
        <div class="lg:w-1/3">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Pesanan</h3>
                
                {{-- Items List --}}
                <div class="space-y-2 mb-4">
                    @foreach($cart as $id => $details)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $details['name'] }} x{{ $details['quantity'] }}</span>
                        <span class="text-gray-900">Rp {{ number_format($details['price'] * $details['quantity'],0,',','.') }}</span>
                    </div>
                    @endforeach
                </div>

                <hr class="my-4">

                {{-- Calculation --}}
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="text-gray-900">Rp {{ number_format($total,0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span class="text-green-600 font-medium">Gratis</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pajak (11%)</span>
                        <span class="text-gray-900">Rp {{ number_format($total * 0.11,0,',','.') }}</span>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Total --}}
                <div class="flex justify-between items-center mb-6">
                    <span class="text-lg font-bold text-gray-900">Total</span>
                    <span class="text-2xl font-bold text-blue-600">
                        Rp {{ number_format($total + ($total * 0.11),0,',','.') }}
                    </span>
                </div>

                {{-- Checkout Button --}}
                <form action="{{ route('user.checkout.index') }}" method="GET">
                    @csrf
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 px-6 rounded-lg font-bold text-lg hover:from-blue-700 hover:to-purple-700 transition duration-200 shadow-lg">
                        Lanjut ke Checkout
                    </button>
                </form>

                <p class="text-center text-gray-500 text-xs mt-3">
                    Dengan melanjutkan, Anda menyetujui syarat dan ketentuan kami
                </p>
            </div>
        </div>
    </div>
    @else
    {{-- Empty Cart --}}
    <div class="text-center py-12">
        <div class="mb-6">
            <i class="fas fa-shopping-cart text-gray-300 text-6xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Keranjang Belanja Kosong</h2>
        <p class="text-gray-500 mb-8 max-w-md mx-auto">
            Belum ada buku yang ditambahkan ke keranjang. Mari mulai berbelanja!
        </p>
        <a href="{{ route('user.products.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition duration-200">
            <i class="fas fa-shopping-bag mr-2"></i>Mulai Belanja
        </a>
    </div>
    @endif
</div>

<style>
/* Custom styles untuk tampilan yang lebih menarik */
.bg-gradient-to-r {
    background: linear-gradient(45deg, #667eea, #764ba2);
}

.shadow-lg {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.sticky {
    position: sticky;
}
</style>

<!-- Font Awesome untuk icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection