Jawaban Jobsheet 6

Tidak, mengirim field total dari form dan hanya memvalidasinya dengan aturan numeric belum cukup untuk mencegah manipulasi total transaksi.

Validasi numeric hanya memastikan bahwa nilai yang dikirim berupa angka. Namun, pengguna masih dapat mengubah nilai tersebut sebelum dikirim ke server. Oleh karena itu, total transaksi tidak seharusnya dipercaya dari input pengguna. Server harus menghitung ulang total berdasarkan data yang tersimpan di database, yaitu harga produk dan jumlah barang (qty). Dengan cara tersebut, meskipun pengguna mencoba mengubah nilai total yang dikirim dari form, nilai yang digunakan oleh sistem tetap berasal dari perhitungan server. Hal ini membuat manipulasi total menjadi lebih sulit karena pengguna tidak dapat menentukan sendiri nilai akhir transaksi.

Kesimpulannya, validasi numeric hanya memeriksa tipe input, bukan kebenaran nilai total. Untuk mencegah manipulasi, total harus dihitung dan ditentukan kembali oleh server berdasarkan data yang dipercaya dari database. 

Pertanyaan Jobsheet 9, Langkah 6: Untuk laporan 100.000 transaksi, format mana yang kalian pilih (CSV, Excel, atau PDF), dan kenapa?

Untuk laporan dengan 100.000 transaksi, kelompok kami memilih format CSV karena lebih ringan dan mendukung pemrosesan data dalam jumlah besar menggunakan streaming. Data dapat ditulis secara bertahap tanpa harus memuat seluruh transaksi ke memori. Excel lebih cocok untuk laporan yang membutuhkan pengolahan dan format tabel, sedangkan PDF lebih sesuai untuk laporan ringkasan yang akan dibaca atau dicetak.