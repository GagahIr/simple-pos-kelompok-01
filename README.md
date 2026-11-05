Jawaban Jobsheet 6

Tidak, mengirim field total dari form dan hanya memvalidasinya dengan aturan numeric belum cukup untuk mencegah manipulasi total transaksi.

Validasi numeric hanya memastikan bahwa nilai yang dikirim berupa angka. Namun, pengguna masih dapat mengubah nilai tersebut sebelum dikirim ke server. Oleh karena itu, total transaksi tidak seharusnya dipercaya dari input pengguna. Server harus menghitung ulang total berdasarkan data yang tersimpan di database, yaitu harga produk dan jumlah barang (qty). Dengan cara tersebut, meskipun pengguna mencoba mengubah nilai total yang dikirim dari form, nilai yang digunakan oleh sistem tetap berasal dari perhitungan server. Hal ini membuat manipulasi total menjadi lebih sulit karena pengguna tidak dapat menentukan sendiri nilai akhir transaksi.

Kesimpulannya, validasi numeric hanya memeriksa tipe input, bukan kebenaran nilai total. Untuk mencegah manipulasi, total harus dihitung dan ditentukan kembali oleh server berdasarkan data yang dipercaya dari database. 

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
