@extends('admin.layouts.app')
@section('content_title', 'Dashboard')

@section('content')
<div class="row">
    {{-- Statistics Cards --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalProducts }}</h3>
                <p>Total Produk</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
            <a href="{{ route('admin.product.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalOrders }}</h3>
                <p>Total Pesanan</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalUsers }}</h3>
                <p>User Terdaftar</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3>{{ $totalMessages }}</h3>
                <p>Pesan Masuk</p>
                @if($unreadMessages > 0)
                <small class="badge badge-danger">{{ $unreadMessages }} pesan belum dibaca</small>
                @endif
            </div>
            <div class="icon">
                <i class="fas fa-envelope"></i>
            </div>
            <a href="{{ route('admin.messages.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    {{-- Recent Orders --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pesanan Terbaru</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-tool">
                        <i class="fas fa-list"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td>{{ $order->customer_name }}</td>
                                <td>
                                    <span class="badge 
                                        @if($order->status == 'pending') badge-warning
                                        @elseif($order->status == 'paid') badge-info
                                        @elseif($order->status == 'processing') badge-primary
                                        @elseif($order->status == 'completed') badge-success
                                        @elseif($order->status == 'cancelled') badge-danger
                                        @else badge-secondary @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    Belum ada pesanan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Messages --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pesan Terbaru dari User</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.messages.index') }}" class="btn btn-tool">
                        <i class="fas fa-list"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Pengirim</th>
                                <th>Subjek</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMessages as $message)
                            <tr class="{{ $message->is_read ? '' : 'bg-light' }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if(!$message->is_read)
                                        <span class="badge badge-danger badge-pill mr-2" title="Belum dibaca"></span>
                                        @endif
                                        <div>
                                            <strong>{{ $message->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $message->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.messages.show', $message->id) }}" 
                                       class="{{ $message->is_read ? 'text-dark' : 'text-primary font-weight-bold' }}">
                                        {{ Str::limit($message->subject, 30) }}
                                    </a>
                                </td>
                                <td>
                                    @if($message->is_read)
                                        <span class="badge badge-success">Dibaca</span>
                                    @else
                                        <span class="badge badge-warning">Baru</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $message->created_at->format('d/m/Y') }}</small>
                                    <br>
                                    <small class="text-muted">{{ $message->created_at->format('H:i') }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Belum ada pesan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-6 text-center">
                        <a href="{{ route('admin.product.index') }}" class="btn btn-app">
                            <i class="fas fa-plus text-success"></i> 
                            <strong>Tambah Produk</strong>
                        </a>
                    </div>
                    <div class="col-md-3 col-6 text-center">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-app">
                            <i class="fas fa-shopping-cart text-primary"></i>
                            <strong>Kelola Pesanan</strong>
                        </a>
                    </div>
                    <div class="col-md-3 col-6 text-center">
                        <a href="{{ route('admin.messages.index') }}" class="btn btn-app">
                            <i class="fas fa-envelope text-warning"></i>
                            <strong>Lihat Pesan</strong>
                            @if($unreadMessages > 0)
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                {{ $unreadMessages }}
                            </span>
                            @endif
                        </a>
                    </div>
                    <div class="col-md-3 col-6 text-center">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-app">
                            <i class="fas fa-users text-info"></i>
                            <strong>Data User</strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-purple {
    background-color: #6f42c1 !important;
    color: white;
}
.bg-purple .small-box-footer {
    background: rgba(0,0,0,0.1);
    color: rgba(255,255,255,0.8);
}
.btn-app {
    position: relative;
}
</style>
@endsection