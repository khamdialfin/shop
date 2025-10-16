<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Models\orderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
     // Tampilkan form checkout
    public function index()
    {
        $cart = session('cart', []);
        if(empty($cart)) {
            return redirect()->route('user.cart.index')->with('error', 'Keranjang belanja kosong!');
        }

        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('user.checkout.index', compact('cart', 'total'));
    }

    // Simpan order
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'payment_method' => 'required|string|in:cod,transfer',
        ]);

        $cart = session('cart', []);
        if(empty($cart)) {
            return redirect()->route('user.cart.index')->with('error', 'Keranjang kosong!');
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => array_sum(array_map(fn($i)=>$i['price']*$i['quantity'],$cart)),
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid',
            'address' => $request->address,
        ]);

        foreach($cart as $productId => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
            ]);
        }

        // Kosongkan session cart
        Session::forget('cart');

        return redirect()->route('user.active-orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }
}
