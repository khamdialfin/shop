<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-4 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-xl font-bold text-gray-900">Pembayaran Pesanan</h3>
            <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        {{-- Modal Content --}}
        <div class="p-4 max-h-96 overflow-y-auto">
            <div id="paymentContent">
                {{-- Content akan di-load via AJAX --}}
            </div>
        </div>
    </div>
</div>

<script>
// Global functions untuk payment
window.confirmCOD = function(orderId, orderNumber) {
    console.log('confirmCOD dipanggil:', orderId, orderNumber);
    
    if (confirm('Konfirmasi pesanan COD? Pesanan akan segera diproses.')) {
        processCodPayment(orderId, orderNumber);
    }
}

window.submitPaymentForm = function(orderId, orderNumber) {
    console.log('submitPaymentForm dipanggil:', orderId, orderNumber);
    
    const methodSelect = document.getElementById('payment_method');
    const fileInput = document.getElementById('payment_proof');
    
    console.log('Method value:', methodSelect?.value);
    console.log('File:', fileInput?.files[0]);
    
    // Validasi method select
    if (!methodSelect || !methodSelect.value) {
        alert('Pilih metode pembayaran terlebih dahulu!');
        methodSelect?.focus();
        return false;
    }
    
    // Validasi file input
    if (!fileInput || !fileInput.files || !fileInput.files.length) {
        alert('Pilih bukti pembayaran terlebih dahulu!');
        fileInput?.focus();
        return false;
    }
    
    // Validasi file size (max 2MB)
    const file = fileInput.files[0];
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran file terlalu besar! Maksimal 2MB.');
        return false;
    }
    
    // Validasi file type
    const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!validTypes.includes(file.type)) {
        alert('Format file tidak didukung! Gunakan JPG, PNG, atau GIF.');
        return false;
    }
    
    // Tampilkan konfirmasi
    if (confirm('Kirim bukti pembayaran? Status akan berubah menjadi "Menunggu Verifikasi Admin".')) {
        console.log('Konfirmasi OK, memproses pembayaran...');
        processPayment(orderId, orderNumber);
        return true;
    }
    
    return false;
}

// Fungsi untuk proses pembayaran transfer
function processPayment(orderId, orderNumber) {
    console.log('processPayment mulai:', orderId, orderNumber);
    
    const form = document.getElementById('paymentForm');
    if (!form) {
        console.error('Form tidak ditemukan!');
        alert('Error: Form tidak ditemukan');
        return;
    }
    
    const formData = new FormData(form);
    const submitBtn = document.querySelector('#paymentForm button[type="button"]');
    
    if (!submitBtn) {
        console.error('Submit button tidak ditemukan!');
        alert('Error: Tombol submit tidak ditemukan');
        return;
    }
    
    // Show loading
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    submitBtn.disabled = true;
    
    console.log('Mengirim data ke server...');
    
    // Kirim via AJAX ke backend
    fetch(`/user/orders/${orderId}/payment/process`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response received:', response);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Data dari server:', data);
        if (data.success) {
            showWaitingStatus('transfer', orderNumber);
        } else {
            alert('Error: ' + (data.message || 'Terjadi kesalahan'));
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses pembayaran: ' + error.message);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Fungsi untuk proses COD
function processCodPayment(orderId, orderNumber) {
    console.log('processCodPayment mulai:', orderId, orderNumber);
    
    const submitBtn = document.querySelector('button[onclick*="confirmCOD"]');
    
    if (!submitBtn) {
        console.error('COD button tidak ditemukan!');
        alert('Error: Tombol COD tidak ditemukan');
        return;
    }
    
    // Show loading
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    submitBtn.disabled = true;
    
    console.log('Mengirim request COD ke server...');
    
    // Kirim via AJAX ke backend
    fetch(`/user/orders/${orderId}/payment/cod`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            order_id: orderId,
            order_number: orderNumber
        })
    })
    .then(response => {
        console.log('COD Response:', response);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('COD Data dari server:', data);
        if (data.success) {
            showWaitingStatus('COD', orderNumber);
        } else {
            alert('Error: ' + (data.message || 'Terjadi kesalahan'));
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('COD Error:', error);
        alert('Terjadi kesalahan saat memproses COD: ' + error.message);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function showWaitingStatus(type, orderNumber) {
    console.log('showWaitingStatus:', type, orderNumber);
    
    const modalContent = document.getElementById('paymentContent');
    if (!modalContent) {
        console.error('Modal content tidak ditemukan!');
        alert('Error: Modal content tidak ditemukan');
        return;
    }
    
    if (type === 'COD') {
        modalContent.innerHTML = `
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Pesanan COD Dikonfirmasi!</h3>
                <p class="text-gray-600 mb-4">Pesanan Anda akan segera diproses dan dikirim.</p>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 inline-block">
                    <p class="text-green-800 font-semibold">Status: Diproses</p>
                </div>
                <button onclick="closePaymentModalAndRefresh()" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 mt-4">
                    Tutup
                </button>
            </div>
        `;
    } else {
        modalContent.innerHTML = `
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Bukti Pembayaran Terkirim!</h3>
                <p class="text-gray-600 mb-4">Terima kasih telah melakukan pembayaran.</p>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <p class="text-yellow-800 font-semibold">
                        <i class="fas fa-user-shield mr-2"></i>Status: Menunggu Verifikasi Admin
                    </p>
                    <p class="text-yellow-700 text-sm mt-1">Admin akan memverifikasi pembayaran Anda dalam 1x24 jam</p>
                </div>
                <button onclick="closePaymentModalAndRefresh()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Tutup
                </button>
            </div>
        `;
    }
    
    console.log('Status waiting ditampilkan');
}

function closePaymentModalAndRefresh() {
    console.log('closePaymentModalAndRefresh dipanggil');
    
    // Tutup modal
    const modal = document.getElementById('paymentModal');
    if (modal) {
        modal.classList.add('hidden');
        console.log('Modal ditutup');
    } else {
        console.error('Modal tidak ditemukan!');
    }
    
    // Refresh halaman untuk update status dari database
    setTimeout(() => {
        console.log('Refresh halaman...');
        window.location.reload();
    }, 1500);
}

function openPaymentModal(orderId) {
    console.log('openPaymentModal dipanggil:', orderId);
    
    const modal = document.getElementById('paymentModal');
    const content = document.getElementById('paymentContent');
    
    if (!modal || !content) {
        console.error('Modal atau content tidak ditemukan!');
        alert('Error: Modal tidak dapat dibuka');
        return;
    }
    
    // Show loading
    content.innerHTML = `
        <div class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-2 text-gray-600">Memuat...</span>
        </div>
    `;
    
    // Show modal
    modal.classList.remove('hidden');
    console.log('Modal ditampilkan');
    
    // Load payment content via AJAX
    fetch(`/user/orders/${orderId}/payment/modal`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            content.innerHTML = html;
            console.log('Payment modal content loaded');
        })
        .catch(error => {
            console.error('Error loading modal content:', error);
            content.innerHTML = `
                <div class="text-center py-4 text-red-600">
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Gagal memuat halaman pembayaran</p>
                    <p class="text-sm mt-2">Error: ${error.message}</p>
                    <button onclick="closePaymentModal()" class="bg-red-600 text-white px-4 py-2 rounded mt-4">
                        Tutup
                    </button>
                </div>
            `;
        });
}

function closePaymentModal() {
    console.log('closePaymentModal dipanggil');
    const modal = document.getElementById('paymentModal');
    if (modal) {
        modal.classList.add('hidden');
        console.log('Modal ditutup');
    }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('paymentModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target.id === 'paymentModal') {
                closePaymentModal();
            }
        });
    }
    
    console.log('Payment modal JavaScript loaded successfully');
});

// Error handling global
window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
});

// Promise rejection handling
window.addEventListener('unhandledrejection', function(e) {
    console.error('Unhandled promise rejection:', e.reason);
});
</script>