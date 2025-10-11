@extends('admin.layouts.app')

@section('content_title', 'Daftar Order')

@section('content')
<div class="card">
    <div class="card-header">
         <h4 class="card-title">Daftar Order</h4>
    </div>
    <div class="card-body">
        @if($orders->count() > 0)
<table class="table table-sm" id="table2">
    <thead>
        <tr class="border-b">
            <th class="p-2">ID Order</th>
            <th class="p-2">User</th>
            <th class="p-2">Total</th>
            <th class="p-2">Status</th>
            <th class="p-2">Tanggal</th>
            <th class="p-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr class="border-b">
            <td class="p-2">{{ $order->id }}</td>
            <td class="p-2">{{ $order->user->name }}</td>
            <td class="p-2">Rp {{ number_format($order->total_price,0,',','.') }}</td>
            <td class="p-2">{{ ucfirst($order->status) }}</td>
            <td class="p-2">{{ $order->created_at->format('d-m-Y H:i') }}</td>
            <td class="p-2">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1 bg-blue-700 text-black rounded hover:bg-blue-800">Detail</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>Belum ada order.</p>
@endif
    </div>
</div>
@endsection
