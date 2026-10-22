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