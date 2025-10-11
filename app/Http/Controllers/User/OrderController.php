<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
  public function activeOrders()
{
    $orders = Order::with('items.product')
        ->where('user_id', Auth::id())
        ->where('confirmed_by_user', false)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('user.orders.active-orders', compact('orders'));
}

// Detail pesanan aktif
public function activeOrdersShow($id)
{
    $order = Order::with('items.product')
        ->where('user_id', Auth::id())
        ->where('confirmed_by_user', false)
        ->findOrFail($id);

    return view('user.orders.active-show', compact('order'));
}

// Konfirmasi pesanan diterima
public function confirm($id)
{
    $order = Order::where('user_id', Auth::id())
        ->where('confirmed_by_user', false)
        ->findOrFail($id);

    $order->confirmed_by_user = true;
    $order->save();

    return redirect()->route('user.orders.index')->with('success', 'Pesanan berhasil dikonfirmasi.');
}

// Simulasi pembayaran
public function pay($id)
{
    $order = Order::where('user_id', Auth::id())->findOrFail($id);

    if($order->payment_status === 'paid') {
        return redirect()->back()->with('error', 'Pesanan sudah dibayar.');
    }

    $order->payment_status = 'paid';
    $order->status = 'paid';
    $order->save();

    return redirect()->back()->with('success', 'Pembayaran berhasil.');
}
    // Riwayat order (confirmed)
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->where('confirmed_by_user', true)
            ->orderBy('created_at','desc')
            ->get();

        return view('user.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->where('confirmed_by_user', true)
            ->findOrFail($id);

        return view('user.orders.show', compact('order'));
    }
    }
