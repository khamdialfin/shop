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

        // Hitung total
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax = $subtotal * 0.11; // Pajak 11%
        $total = $subtotal + $tax;


        return view('user.checkout.index', compact('cart', 'subtotal', 'tax', 'total'));
    }

   // Simpan order
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'payment_method' => 'required|string|in:transfer,ewallet,cod',
        ]);

        $cart = session('cart', []);
        if(empty($cart)) {
            return redirect()->route('user.cart.index')->with('error', 'Keranjang belanja kosong!');
        }

        // Hitung total
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax = $subtotal * 0.11;
        $total = $subtotal + $tax;

        try {
            DB::beginTransaction();

            // Create order dengan struktur baru
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                
                // Informasi customer
                'customer_name' => $request->name,
                'customer_email' => $request->email,
                'customer_phone' => $request->phone,
                'customer_address' => $request->address,
                'customer_city' => $request->city,
                'customer_postal_code' => $request->postal_code,
                
                // Informasi harga
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                
                // Status dan pembayaran
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'payment_status' => 'pending',
                'confirmed_by_user' => false,
            ]);

            // Create order items
            foreach($cart as $productId => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'product_name' => $details['name'],
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'subtotal' => $details['price'] * $details['quantity'],
                ]);
            }

            DB::commit();

            // Kosongkan session cart
            Session::forget('cart');

            return redirect()->route('user.orders.index', $order->id)
                           ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
