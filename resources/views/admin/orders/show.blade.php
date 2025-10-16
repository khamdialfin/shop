@extends('admin.layouts.app')

@section('title', 'Detail Order #' . $order->id)

@section('content')
<div class="row">
    <div class="col-12">
        {{-- Flash Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h4>Detail Order #{{ $order->order_number }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Order</h6>
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>User</strong></td>
                                <td>{{ $order->user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>{{ $order->customer_email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Telepon</strong></td>
                                <td>{{ $order->customer_phone }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>{{ $order->customer_address }}, {{ $order->customer_city }} {{ $order->customer_postal_code }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Order</strong></td>
                                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Metode Pembayaran</strong></td>
                                <td>{{ ucfirst($order->payment_method) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Status Order</h6>
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>Status Order</strong></td>
                                <td>
                                    <span class="badge 
                                        @if($order->status == 'pending') bg-warning
                                        @elseif($order->status == 'paid') bg-info
                                        @elseif($order->status == 'processing') bg-primary
                                        @elseif($order->status == 'completed') bg-success
                                        @elseif($order->status == 'cancelled') bg-danger
                                        @else bg-secondary @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Order</strong></td>
                                <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                            @if($order->payment_proof)
                            <tr>
                                <td><strong>Bukti Pembayaran</strong></td>
                                <td>
                                    <a href="{{ asset('storage/' . $order->payment_proof) }}" 
                                       target="_blank" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-image"></i> Lihat Bukti
                                    </a>
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                {{-- Form Ubah Status --}}
                <div class="row mt-4">
                    <div class="col-md-6">
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="status"><strong>Ubah Status Order</strong></label>
                                <select name="status" class="form-control" required>
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">
                                <i class="fas fa-sync-alt"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Produk --}}
        <div class="card">
            <div class="card-header">
                <h4>Produk dalam Order</h4>
            </div>
            <div class="card-body">
                @if($order->items->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="rounded me-3" 
                                                 width="50" height="50">
                                        @endif
                                        <div>
                                            <strong>{{ $item->product->name }}</strong>
                                            @if($item->product->kategori)
                                                <br><small class="text-muted">{{ $item->product->kategori->name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="table-primary">
                                <td colspan="3" class="text-end"><strong>Total Order:</strong></td>
                                <td><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Belum ada produk dalam order ini.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection