<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Models\Product;
use App\Models\orderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
     public function index()
    {
         $cart = session()->get('cart', []); // ambil data cart dari session
        return view('user.cart', compact('cart'));
    }
    
    // Tambahkan produk ke keranjang
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // Jika produk sudah ada di cart, tambahkan quantity
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => 1,
                "image" => $product->image
            ];
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }
    // Hapus produk dari keranjang
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function update(Request $request, $id)
    {
    $action = $request->input('action');
    
    if ($action === 'increase') {
        // Increase quantity
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
            session()->put('cart', $cart);
        }
    } elseif ($action === 'decrease') {
        // Decrease quantity
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            if($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            } else {
                // Jika quantity 1, hapus item
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
        }
    } else {
        // Update quantity manual (dari input number)
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
    }
    
    return redirect()->back()->with('success', 'Keranjang berhasil diperbarui!');

    }
    public function checkout()
    {
       $cart = session()->get('cart', []);
    if(!$cart || count($cart) == 0){
        return redirect()->back()->with('error', 'Keranjang kosong!');
    }

    $total = 0;
    foreach($cart as $details){
        $total += $details['price'] * $details['quantity'];
    }

    // Buat order
    $order = Order::create([
        'user_id' => Auth::id(),
        'total_price' => $total,
        'status' => 'pending',
    ]);

    // Buat order items
    foreach($cart as $id => $details){
        orderItem::create([
            'order_id' => $order->id,
            'product_id' => $id,
            'quantity' => $details['quantity'],
            'price' => $details['price'],
        ]);
    }

    // Hapus cart
    session()->forget('cart');

    return redirect()->route('user.home')->with('success', 'Checkout berhasil! Order sudah tercatat.');
    }

    public function clear()
    {
    session()->forget('cart');
    return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan!');
    }
}
