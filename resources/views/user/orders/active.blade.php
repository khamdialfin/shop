@extends('layouts.user.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Pesanan Aktif</h2>

@if($orders->count())
    <table class="w-full border rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">No</th>
                <th class="p-2">Tanggal</th>
                <th class="p-2">Total</th>
                <th class="p-2">Status</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr class="border-b">
                <td class="p-2">{{ $loop->iteration }}</td>
                <td class="p-2">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                <td class="p-2">Rp {{ number_format($order->total_price,0,',','.') }}</td>
                <td class="p-2">{{ ucfirst($order->status) }}</td>
                <td class="p-2">
                    <a href="{{ route('user.active-orders.show', $order->id) }}" class="px-2 py-1 bg-blue-700 text-white rounded hover:bg-blue-800">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
<p>Belum ada pesanan aktif.</p>
@endif
@endsection
