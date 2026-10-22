# Pengukuran Memori: get() vs lazyById()

Dataset: sekitar 52.500 row pada tabel `transactions`.

| Cara baca data | Peak memory (MB) |
|---|---|
| `Transaction::lazyById()` | Belum diukur |
| `Transaction::get()` | Belum diukur |

## Kesimpulan Kelompok

Pengukuran ini dilakukan untuk membandingkan penggunaan memori antara metode `get()` dan `lazyById()` pada Laravel. Metode `get()` mengambil seluruh data sekaligus, sedangkan `lazyById()` membaca data secara bertahap. Hasil pengukuran akan digunakan untuk mengetahui metode yang lebih hemat memori.