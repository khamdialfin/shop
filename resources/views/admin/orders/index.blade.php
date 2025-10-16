@extends('admin.layouts.app')

@section('content_title', 'Daftar Order')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Daftar Order</h4>
    </div>
    <div class="card-body">
        @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>ID Order</th>
                        <th>User</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Metode Bayar</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>#{{ $order->order_number }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
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
                        <td>{{ ucfirst($order->payment_method) }}</td>
                        <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Belum ada order.
        </div>
        @endif
    </div>
</div>
@endsection