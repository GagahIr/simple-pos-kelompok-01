Jawaban Jobsheet 6

Tidak, mengirim field total dari form dan hanya memvalidasinya dengan aturan numeric belum cukup untuk mencegah manipulasi total transaksi.

Validasi numeric hanya memastikan bahwa nilai yang dikirim berupa angka. Namun, pengguna masih dapat mengubah nilai tersebut sebelum dikirim ke server. Oleh karena itu, total transaksi tidak seharusnya dipercaya dari input pengguna. Server harus menghitung ulang total berdasarkan data yang tersimpan di database, yaitu harga produk dan jumlah barang (qty). Dengan cara tersebut, meskipun pengguna mencoba mengubah nilai total yang dikirim dari form, nilai yang digunakan oleh sistem tetap berasal dari perhitungan server. Hal ini membuat manipulasi total menjadi lebih sulit karena pengguna tidak dapat menentukan sendiri nilai akhir transaksi.

Kesimpulannya, validasi numeric hanya memeriksa tipe input, bukan kebenaran nilai total. Untuk mencegah manipulasi, total harus dihitung dan ditentukan kembali oleh server berdasarkan data yang dipercaya dari database. 

Jawaban Jobsheet 6

Tidak, mengirim field total dari form dan hanya memvalidasinya dengan aturan numeric belum cukup untuk mencegah manipulasi total transaksi.

Validasi numeric hanya memastikan bahwa nilai yang dikirim berupa angka. Namun, pengguna masih dapat mengubah nilai tersebut sebelum dikirim ke server. Oleh karena itu, total transaksi tidak seharusnya dipercaya dari input pengguna. Server harus menghitung ulang total berdasarkan data yang tersimpan di database, yaitu harga produk dan jumlah barang (qty). Dengan cara tersebut, meskipun pengguna mencoba mengubah nilai total yang dikirim dari form, nilai yang digunakan oleh sistem tetap berasal dari perhitungan server. Hal ini membuat manipulasi total menjadi lebih sulit karena pengguna tidak dapat menentukan sendiri nilai akhir transaksi.

Kesimpulannya, validasi numeric hanya memeriksa tipe input, bukan kebenaran nilai total. Untuk mencegah manipulasi, total harus dihitung dan ditentukan kembali oleh server berdasarkan data yang dipercaya dari database.

Jobsheet 7: Skenario Uji Manual per Peran

Persiapan sebelum semua skenario:

php artisan migrate:fresh --seed
php artisan serve dan npm run dev (terminal kedua)
Buka http://127.0.0.1:8000/login

Akun uji (kata sandi semua akun: password):

Peran	Email	Nama
admin	admin@pos.test	Admin Kafe
manager	manager@pos.test	Manager Kafe
kasir	kasir@pos.test	Kasir Kafe
Skenario 1: Admin
No	Langkah	Hasil yang diharapkan
1	Login dengan admin@pos.test	Masuk ke halaman /pos. Navigasi menampilkan Kasir, Transaksi, Produk, Kategori, dan "Admin Kafe (admin)"
2	Buka /products	Daftar produk tampil normal
3	Buka /categories	Daftar kategori tampil normal
4	Buka /transactions	Riwayat transaksi tampil normal
5	Klik Keluar	Diarahkan ke /login. Membuka /products lagi mengarahkan ke /login
Skenario 2: Manager
No	Langkah	Hasil yang diharapkan
1	Login dengan manager@pos.test	Masuk ke halaman /pos. Navigasi menampilkan Kasir, Transaksi, dan "Manager Kafe (manager)". Menu Produk dan Kategori tidak ada
2	Buka /transactions	Riwayat transaksi tampil normal
3	Buka /products lewat address bar	Halaman error 403 dengan pesan "Anda tidak memiliki akses ke halaman ini.", bukan daftar produk
4	Buka /categories lewat address bar	Halaman error 403 yang sama
5	Klik Keluar	Diarahkan ke /login
Skenario 3: Kasir
No	Langkah	Hasil yang diharapkan
1	Login dengan kasir@pos.test	Masuk ke halaman /pos. Navigasi menampilkan Kasir, Transaksi, dan "Kasir Kafe (kasir)". Menu Produk dan Kategori tidak ada
2	Buka /pos	Halaman kasir tampil normal
3	Buka /transactions	Riwayat transaksi tampil normal
4	Buka /products lewat address bar	Halaman error 403 dengan pesan "Anda tidak memiliki akses ke halaman ini.", bukan daftar produk
5	Buka /categories lewat address bar	Halaman error 403 yang sama
6	Klik Keluar	Diarahkan ke /login
Skenario 4: Tamu (belum login)
No	Langkah	Hasil yang diharapkan
1	Buka /products tanpa login	Diarahkan ke /login
2	Buka /info tanpa login	Halaman informasi tampil
3	Login dengan kata sandi salah	Pesan "Email atau kata sandi salah."
4	Login sebagai peran apa pun, lalu buka /info atau /login	Diarahkan ke /pos
5	curl -i http://127.0.0.1:8000/pos -H "Accept: application/json" (tanpa login)	Status 401 Unauthorized, isi {"message":"Unauthenticated."}
Akun nonaktif
No	Langkah	Hasil yang diharapkan
1	Di php artisan tinker: App\Models\User::where('email', 'kasir@pos.test')->update(['is_active' => false]);	Perintah berhasil
2	Login dengan kasir@pos.test dan kata sandi benar	Ditolak dengan pesan "Akun dinonaktifkan. Hubungi admin."
3	Aktifkan kembali dengan update(['is_active' => true]), lalu login lagi	Login berhasil


Pertanyaan Jobsheet 9, Langkah 6: Untuk laporan 100.000 transaksi, format mana yang kalian pilih (CSV, Excel, atau PDF), dan kenapa?
Untuk laporan dengan 100.000 transaksi, kelompok kami memilih format CSV karena lebih ringan dan mendukung pemrosesan data dalam jumlah besar menggunakan streaming. Data dapat ditulis secara bertahap tanpa harus memuat seluruh transaksi ke memori. Excel lebih cocok untuk laporan yang membutuhkan pengolahan dan format tabel, sedangkan PDF lebih sesuai untuk laporan ringkasan yang akan dibaca atau dicetak.

## Jawaban Jobsheet 9: Usulan Proses Lain untuk Queued Job (Sasa)

Proses lain di Simple POS yang layak dipindahkan ke queued job adalah **ekspor laporan penjualan ke Excel untuk data dalam jumlah besar**, selain import produk yang dibahas pada jobsheet.

Saat ini, metode `exportExcel()` pada `app/Http/Controllers/ReportController.php` memanggil `Excel::download()` menggunakan `SalesReportExport`. Pembuatan file dilakukan dalam request yang sama, sehingga admin harus menunggu sampai file selesai dibuat sebelum dapat mengunduhnya.

Alasan proses ini cocok dipindahkan ke queued job:

1. **Pemrosesan bisa memakan waktu lama.** Laporan dengan puluhan hingga ratusan ribu transaksi membutuhkan pembacaan data, pemetaan setiap baris, dan pembuatan file Excel. Semakin banyak data, semakin lama prosesnya.
2. **Halaman dapat segera memberikan respons.** Setelah permintaan ekspor masuk antrean, aplikasi dapat menampilkan pesan bahwa laporan sedang diproses. Admin bisa melanjutkan pekerjaan lain dan mengunduh file setelah tersedia, sehingga mengurangi risiko request web mengalami timeout.
3. **Kegagalan lebih mudah ditangani.** Job dapat diberi batas percobaan ulang (`tries`), jeda antarpercobaan (`backoff`), dan batas waktu (`timeout`). Jika tetap gagal, job dapat diperiksa melalui `failed_jobs`.

Alur yang diusulkan: admin memilih rentang tanggal dan meminta ekspor, lalu aplikasi mengirim job dengan parameter tanggal serta ID admin peminta. Queue worker mengambil data sesuai filter dan menyimpan file laporan. Setelah selesai, aplikasi menampilkan status selesai beserta tautan unduh yang hanya dapat diakses admin peminta.

Queue memindahkan pekerjaan ke latar belakang, tetapi tidak otomatis mengurangi penggunaan memori. Karena itu, data tetap perlu diproses secara bertahap (chunk), dan hasil ekspor menggunakan lokasi file yang sama untuk setiap permintaan agar percobaan ulang tidak menghasilkan banyak file duplikat. Usulan ini belum mengubah implementasi ekspor yang ada.
