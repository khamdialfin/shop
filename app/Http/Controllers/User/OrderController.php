<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
  public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

   // Konfirmasi pesanan diterima dan kurangi stok
    public function confirm($id)
    {
        DB::beginTransaction();
        
        try {
            $order = Order::with('items.product')->where('user_id', Auth::id())->findOrFail($id);
            
            // Validasi: hanya order yang statusnya 'completed' yang bisa dikonfirmasi
            if ($order->status !== 'completed') {
                return redirect()->back()->with('error', 'Pesanan belum selesai atau belum bisa dikonfirmasi.');
            }

            // Validasi: pastikan belum dikonfirmasi sebelumnya
            if ($order->confirmed_by_user) {
                return redirect()->back()->with('info', 'Pesanan sudah dikonfirmasi sebelumnya.');
            }

            // Kurangi stok untuk setiap item dalam pesanan
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                
                if ($product) {
                    // Validasi stok cukup
                    if ($product->stock < $item->quantity) {
                        throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                    }
                    
                    // Kurangi stok
                    $product->decrement('stock', $item->quantity);
                    
                    // Log info
                    Log::info("Stok produk {$product->name} dikurangi {$item->quantity}. Stok sekarang: {$product->stock}");
                } else {
                    throw new \Exception("Produk dengan ID {$item->product_id} tidak ditemukan.");
                }
            }

            // Update status konfirmasi user (TANPA confirmed_at)
            $order->update([
                'confirmed_by_user' => true
                // Hapus 'confirmed_at' => now() karena kolom tidak ada
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi! Stok produk telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Error konfirmasi pesanan: " . $e->getMessage());
            
            return redirect()->back()->with('error', 'Gagal mengonfirmasi pesanan: ' . $e->getMessage());
        }
    }
    // Method untuk proses pembayaran (AJAX)
    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'payment_method' => 'required|string',
        ]);

        $order = Order::where('user_id', Auth::id())
            ->where('status', 'pending') // Ubah dari payment_status ke status
            ->findOrFail($id);

        try {
            // Upload bukti pembayaran
            if ($request->hasFile('payment_proof')) {
                $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
            }

            // Update status order menjadi 'paid'
            $order->update([
                'status' => 'paid', // Langsung jadi paid setelah upload bukti
                'payment_method' => $request->payment_method,
                'payment_proof' => $proofPath ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk pembayaran COD (AJAX)
    public function processCodPayment($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('status', 'pending') // Ubah dari payment_status ke status
            ->findOrFail($id);

        try {
            $order->update([
                'status' => 'processing', // COD langsung processing
                'payment_method' => 'cod',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan COD berhasil dibuat! Pesanan akan segera diproses.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

   
    public function showPaymentModal($order) // GANTI $id MENJADI $order
    {
        $order = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($order); // GANTI $id MENJADI $order

        return view('user.orders.payment-modal-content', compact('order'));
    }

}
