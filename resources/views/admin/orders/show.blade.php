@extends('admin.layouts.app')

@section('title', 'Detail Order #' . $order->id)

@section('content')
<div class="card">
    <div class="card-body">
         {{-- Flash Message --}}
    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <h2 class="text-2xl font-bold mb-4">Detail Order #{{ $order->id }}</h2>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <p><strong>User:</strong> {{ $order->user->name }}</p>
            <p><strong>Total:</strong> Rp {{ number_format($order->total_price,0,',','.') }}</p>
        </div>
        <div>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Tanggal:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</p>
        </div>
    </div>

    {{-- Form Ubah Status --}}
    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="flex items-center gap-2 mb-6">
        @csrf
        <select name="status" class="border rounded px-3 py-2">
            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
        <button type="submit" class="btn btn-primary">Update Status</button>
    </form>

    </div>
</div>
<div class="card">
    <div class="card-body">
         {{-- Tabel Produk --}}
    <h3 class="text-xl font-semibold mb-2">Produk dalam Order</h3>
    @if($order->items->count() > 0)
    <table class="table table-sm" id="table2">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 border-b">Produk</th>
                <th class="p-3 border-b">Harga</th>
                <th class="p-3 border-b">Jumlah</th>
                <th class="p-3 border-b">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr class="border-b">
                <td class="p-3">{{ $item->product->name }}</td>
                <td class="p-3">Rp {{ number_format($item->price,0,',','.') }}</td>
                <td class="p-3">{{ $item->quantity }}</td>
                <td class="p-3">Rp {{ number_format($item->price * $item->quantity,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="text-gray-500 mt-2">Belum ada produk dalam order ini.</p>
    @endif
    </div>
</div>

@endsection
