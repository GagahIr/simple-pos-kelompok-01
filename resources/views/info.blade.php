<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Informasi - Simple POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 py-10 px-4">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6 sm:p-8">
        <div class="border-b border-slate-200 pb-4 mb-6">
            <span class="inline-block bg-slate-900 text-white text-xs px-2.5 py-1 rounded font-medium mb-2">Halaman Informasi Tamu</span>
            <h1 class="text-2xl font-bold text-slate-800">Tentang Simple POS</h1>
            <p class="text-sm text-slate-600 mt-1">Sistem kasir dan manajemen transaksi penjualan kafe berbasis web modern.</p>
        </div>

        <div class="space-y-6 text-slate-700 text-sm">
            <div>
                <h2 class="text-base font-semibold text-slate-900 mb-2">Gambaran Umum</h2>
                <p class="leading-relaxed">
                    Simple POS dirancang untuk mempermudah operasional kasir dalam mencatat transaksi pesanan,
                    menghitung subtotal keranjang belanja secara real-time, mengelola stok inventaris produk,
                    serta menyediakan laporan riwayat transaksi harian.
                </p>

                <p class="font-semibold mt-8">Informasi Keamanan & Akses:</p>
                <p class="mt-1">
                    Halaman ini dikhususkan bagi pengunjung/tamu yang belum login. Pengguna yang telah berhasil login akan secara otomatis diarahkan langsung ke halaman kasir (<code>/pos</code>).
                </p>
            </div>

        </div>

        <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs text-slate-500">Sudah memiliki akun pengguna?</span>
            <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-slate-900 text-white px-5 py-2.5 rounded-md text-sm font-medium hover:bg-slate-800 transition">
                Masuk ke Simple POS &rarr;
            </a>
        </div>
    </div>
</body>
</html>
