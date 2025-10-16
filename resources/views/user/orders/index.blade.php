@extends('layouts.user.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Riwayat Pesanan</h1>

    @if($orders->count())
        <div class="space-y-6">
            @foreach($orders as $order)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                {{-- Order Header --}}
                <div class="border-b border-gray-200 p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Pesanan #{{ $order->order_number }}</h2>
                            <p class="text-gray-600 mt-1">
                                {{ $order->created_at->translatedFormat('d F Y') }} pukul {{ $order->created_at->format('H:i') }}
                            </p>
                            <p class="text-gray-600">{{ $order->items->count() }} item</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'paid') bg-blue-100 text-blue-800
                                @elseif($order->status == 'processing') bg-purple-100 text-purple-800
                                @elseif($order->status == 'completed') bg-green-100 text-green-800
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($order->status == 'pending') Menunggu Pembayaran
                                @elseif($order->status == 'paid') Menunggu Verifikasi
                                @elseif($order->status == 'processing') Diproses
                                @elseif($order->status == 'completed') Selesai
                                @elseif($order->status == 'cancelled') Dibatalkan
                                @else {{ ucfirst($order->status) }} @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {{-- Left: Products --}}
                        <div class="lg:col-span-2">
                            <div class="space-y-4">
                                @foreach($order->items as $item)
                                <div class="flex items-center justify-between py-2">
                                    <div class="flex items-center space-x-3">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-12 h-12 object-cover rounded">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                <i class="fas fa-book text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-gray-600">{{ $item->quantity }}x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <span class="font-semibold text-gray-900">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </span>
                                </div>
                                @endforeach
                            </div>

                            {{-- Shipping Address --}}
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="font-semibold text-gray-900 mb-2">Alamat Pengiriman</h4>
                                <p class="text-gray-600 text-sm">{{ $order->customer_address }}</p>
                                <p class="text-gray-600 text-sm">{{ $order->customer_city }}, {{ $order->customer_postal_code }}</p>
                                <p class="text-gray-600 text-sm">{{ $order->customer_phone }}</p>
                            </div>
                        </div>

                        {{-- Right: Payment Summary --}}
                        <div class="lg:col-span-1">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-4">Informasi Pesanan</h4>
                                
                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Email:</span>
                                        <span class="text-gray-900">{{ $order->customer_email }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Metode Bayar:</span>
                                        <span class="text-gray-900">{{ ucfirst($order->payment_method) }}</span>
                                    </div>
                                </div>

                                <hr class="my-4">

                                {{-- Order Summary --}}
                                <div class="space-y-2 mb-4">
                                    @foreach($order->items as $item)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">{{ $item->product->name }}</span>
                                        <span class="text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                    </div>
                                    @endforeach
                                    
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Subtotal</span>
                                        <span class="text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Ongkos Kirim</span>
                                        <span class="text-green-600">Gratis</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Pajak (11%)</span>
                                        <span class="text-gray-900">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <hr class="my-4">

                                {{-- Total --}}
                                <div class="flex justify-between items-center mb-4">
                                    <span class="font-bold text-gray-900">Total</span>
                                    <span class="text-xl font-bold text-blue-600">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </span>
                                </div>

                                {{-- Tombol Aksi --}}
                                @if($order->status == 'pending')
                                    <button onclick="openPaymentModal({{ $order->id }})" 
                                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-lg font-bold hover:from-blue-700 hover:to-purple-700 transition duration-200">
                                        <i class="fas fa-credit-card mr-2"></i>Bayar Sekarang
                                    </button>
                                @elseif($order->status == 'paid')
                                    <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-3 text-yellow-800 text-center">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span class="font-semibold">Menunggu Verifikasi Admin</span>
                                        <p class="text-xs mt-1">Pembayaran sedang diverifikasi</p>
                                    </div>
                                @elseif($order->status == 'processing')
                                    <div class="bg-purple-100 border border-purple-300 rounded-lg p-3 text-purple-800 text-center">
                                        <i class="fas fa-truck mr-2"></i>
                                        <span class="font-semibold">Pesanan Diproses</span>
                                        <p class="text-xs mt-1">Pesanan sedang dikemas</p>
                                    </div>
                                @elseif($order->status == 'completed' && !$order->confirmed_by_user)
                                    <form action="{{ route('user.orders.confirm', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg font-bold hover:bg-green-700 transition duration-200">
                                            <i class="fas fa-check mr-2"></i>Konfirmasi Diterima
                                        </button>
                                    </form>
                                @elseif($order->status == 'completed' && $order->confirmed_by_user)
                                    <div class="bg-green-100 border border-green-300 rounded-lg p-3 text-green-800 text-center">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <span class="font-semibold">Pesanan Selesai</span>
                                        <p class="text-xs mt-1">Terima kasih telah berbelanja</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-12">
            <div class="mb-6">
                <i class="fas fa-shopping-bag text-gray-300 text-6xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Belum Ada Pesanan</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">
                Anda belum memiliki pesanan. Mari mulai berbelanja dan temukan buku favorit Anda!
            </p>
            <a href="{{ route('user.products.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition duration-200">
                <i class="fas fa-shopping-bag mr-2"></i>Mulai Belanja
            </a>
        </div>
    @endif
</div>

<style>
.bg-gradient-to-r {
    background: linear-gradient(45deg, #667eea, #764ba2);
}
</style>

<!-- Font Awesome untuk icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@include('components.payment-modal')
@endsection