@extends('layouts.user.app')

@section('title', 'Hubungi Admin')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Page Header --}}
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Hubungi Admin</h1>
        <p class="text-gray-600 text-lg">
            Punya pertanyaan atau butuh bantuan? Tim admin kami siap membantu Anda.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Contact Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Kirim Pesan</h2>

                {{-- Success Message --}}
                @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <div>
                            <p class="font-semibold">Pesan Terkirim!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Error Message --}}
                @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                        <div>
                            <p class="font-semibold">Gagal Mengirim Pesan</p>
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <form action="{{ route('user.contact.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name"
                                   value="{{ old('name', Auth::user()->name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   required
                                   placeholder="Masukkan nama lengkap Anda">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email"
                                   value="{{ old('email', Auth::user()->email) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   required
                                   placeholder="email@example.com">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Subject --}}
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                Subjek Pesan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="subject" 
                                   name="subject"
                                   value="{{ old('subject') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   required
                                   placeholder="Contoh: Pertanyaan tentang produk, Kendala pembayaran, dll.">
                            @error('subject')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Message --}}
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Isi Pesan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="message" 
                                      name="message" 
                                      rows="6"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                      required
                                      placeholder="Tulis pesan detail Anda di sini...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">Minimal 10 karakter, maksimal 2000 karakter</p>
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex gap-4">
                            <button type="submit" 
                                    class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 flex items-center justify-center">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Kirim Pesan
                            </button>
                            
                            <a href="{{ route('user.home') }}" 
                               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Contact Information --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Informasi Kontak</h2>
                
                <div class="space-y-6">
                    {{-- Response Time --}}
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Waktu Respon</h3>
                            <p class="text-gray-600 text-sm mt-1">
                                Admin biasanya merespon dalam 1-2 jam pada jam operasional
                            </p>
                        </div>
                    </div>

                    {{-- Contact Hours --}}
                    <div class="flex items-start">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class="fas fa-business-time text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Jam Operasional</h3>
                            <p class="text-gray-600 text-sm mt-1">
                                Senin - Minggu<br>
                                08:00 - 22:00 WIB
                            </p>
                        </div>
                    </div>

                    {{-- Response Method --}}
                    <div class="flex items-start">
                        <div class="bg-purple-100 p-3 rounded-full mr-4">
                            <i class="fas fa-envelope text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Metode Respon</h3>
                            <p class="text-gray-600 text-sm mt-1">
                                Admin akan membalas melalui email yang Anda daftarkan
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-blue-50 rounded-lg shadow-md p-6 mt-6">
                <h3 class="font-semibold text-blue-900 mb-4">Butuh Bantuan Cepat?</h3>
                <div class="space-y-3">
                    <a href="{{ route('user.orders.index') }}" 
                       class="w-full bg-white text-blue-600 py-2 px-4 rounded-lg border border-blue-200 hover:bg-blue-100 transition duration-200 flex items-center justify-center text-sm">
                        <i class="fas fa-truck mr-2"></i>
                        Cek Status Pesanan
                    </a>
                    <a href="{{ route('user.products.index') }}" 
                       class="w-full bg-white text-blue-600 py-2 px-4 rounded-lg border border-blue-200 hover:bg-blue-100 transition duration-200 flex items-center justify-center text-sm">
                        <i class="fas fa-book mr-2"></i>
                        Lihat Katalog Produk
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-blue-100 {
        background-color: #dbeafe;
    }
    .bg-green-100 {
        background-color: #dcfce7;
    }
    .bg-purple-100 {
        background-color: #f3e8ff;
    }
</style>
@endsection