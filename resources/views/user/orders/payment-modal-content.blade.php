<div>
    {{-- Order Info --}}
    <div class="mb-4 p-3 bg-blue-50 rounded-lg">
        <p class="font-semibold text-blue-800">Pesanan #{{ $order->order_number }}</p>
        <p class="text-blue-700 text-sm">Total: <span class="font-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</span></p>
    </div>

    {{-- Payment Methods --}}
    @if($order->payment_method === 'cod')
        {{-- COD Payment --}}
        <div class="mb-4">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <i class="fas fa-money-bill-wave text-yellow-600 text-xl mr-3"></i>
                    <div>
                        <h3 class="font-semibold text-yellow-800">Bayar di Tempat (COD)</h3>
                        <p class="text-yellow-700 text-sm">Bayar ketika pesanan sampai</p>
                    </div>
                </div>
            </div>

            <button type="button" onclick="window.confirmCOD({{ $order->id }}, '{{ $order->order_number }}')" 
                    class="w-full bg-green-600 text-white py-3 px-6 rounded-lg font-bold hover:bg-green-700 transition duration-200 mt-3">
                <i class="fas fa-check-circle mr-2"></i>Konfirmasi Pesanan COD
            </button>
        </div>

    @else
        {{-- Transfer/E-wallet Payment --}}
        <div class="space-y-3">
            {{-- Transfer Bank --}}
            <div class="border border-gray-300 rounded-lg p-3 hover:border-blue-500 transition duration-200">
                <div class="flex items-center mb-2">
                    <i class="fas fa-university text-blue-600 text-lg mr-2"></i>
                    <div>
                        <h3 class="font-semibold text-gray-900 text-sm">Transfer Bank</h3>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded p-2 text-xs">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="font-semibold">BCA</span><br>
                            <span>123-456-7890</span><br>
                            <span class="text-gray-600">PT. Toko Buku</span>
                        </div>
                        <div>
                            <span class="font-semibold">Mandiri</span><br>
                            <span>987-654-3210</span><br>
                            <span class="text-gray-600">PT. Toko Buku</span>
                        </div>
                    </div>
                    <p class="text-red-600 font-semibold mt-1">Transfer: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- E-Wallet --}}
            <div class="border border-gray-300 rounded-lg p-3 hover:border-green-500 transition duration-200">
                <div class="flex items-center mb-2">
                    <i class="fas fa-wallet text-green-600 text-lg mr-2"></i>
                    <div>
                        <h3 class="font-semibold text-gray-900 text-sm">E-Wallet</h3>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded p-2 text-xs">
                    <div class="flex justify-between mb-2">
                        <div class="text-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-1">
                                <span class="font-bold text-blue-600 text-xs">G</span>
                            </div>
                            <span class="text-xs">GoPay</span>
                        </div>
                        <div class="text-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-1">
                                <span class="font-bold text-purple-600 text-xs">O</span>
                            </div>
                            <span class="text-xs">OVO</span>
                        </div>
                        <div class="text-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-1">
                                <span class="font-bold text-green-600 text-xs">D</span>
                            </div>
                            <span class="text-xs">DANA</span>
                        </div>
                    </div>
                    <p class="text-red-600 font-semibold">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Upload Proof Form --}}
        <form id="paymentForm" class="mt-4">
            @csrf
            
            <div class="mb-3">
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Metode Pembayaran <span class="text-red-500">*</span>
                </label>
                <select name="payment_method" id="payment_method" required 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Metode --</option>
                    <option value="transfer_bca">Transfer BCA</option>
                    <option value="transfer_mandiri">Transfer Mandiri</option>
                    <option value="gopay">GoPay</option>
                    <option value="ovo">OVO</option>
                    <option value="dana">DANA</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Bukti Pembayaran <span class="text-red-500">*</span>
                </label>
                <input type="file" name="payment_proof" id="payment_proof" required 
                       accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-gray-500 text-xs mt-1">Format: JPG, PNG, GIF (Maks. 2MB)</p>
            </div>

            <button type="button" onclick="window.submitPaymentForm({{ $order->id }}, '{{ $order->order_number }}')" 
                    class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-bold hover:bg-blue-700 transition duration-200 text-sm">
                <i class="fas fa-paper-plane mr-2"></i>Kirim Bukti Pembayaran
            </button>
        </form>
    @endif

    {{-- Important Notes --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-4">
        <h3 class="font-semibold text-blue-800 text-sm mb-1">
            <i class="fas fa-info-circle mr-1"></i>Penting!
        </h3>
        <ul class="text-blue-700 text-xs space-y-1">
            <li>• Pastikan nominal transfer sesuai</li>
            <li>• Upload bukti yang jelas dan terbaca</li>
            <li>• Pesanan diproses setelah verifikasi</li>
        </ul>
    </div>
</div>