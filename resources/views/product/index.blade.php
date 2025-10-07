@extends('layouts.app')
@section('content_title', 'Produk')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0">Daftar Produk</h4>
        <div class="d-flex justify-content-end">
             <x-product.form-product />
        </div>
    </div>

    <div class="card-body">
        {{-- Pesan error validasi --}}
        @if ($errors->any())
            <div class="alert alert-danger d-flex flex-column">
                @foreach ($errors->all() as $error)
                    <small class="text-white my-1">{{ $error }}</small>
                @endforeach
            </div>
        @endif

        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Tabel produk --}}
            <table class="table table-sm" id="table2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         width="60" class="rounded">
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->kategori->nama_kategori ?? '-' }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- Tombol edit pakai komponen yang sama --}}
                                     <x-product.form-product :id="$product->id" />

                                    {{-- Tombol hapus --}}
                                   <a href="{{ route('master-data.product.destroy', $product->id) }}" data-confirm-delete="true" class="btn btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">
                                Belum ada data produk
                            </td>
                        </tr>
                    @endforelse
                </tbody>
         </table>
    </div>
</div>
@endsection
