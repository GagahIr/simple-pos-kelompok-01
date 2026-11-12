<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, SkipsOnFailure
{
    use SkipsFailures; // menyimpan row yang gagal validasi, bisa dibaca lewat $import->failures()

    public int $created = 0;
    public int $updated = 0;

    public function model(array $row)
    {
        // updateOrCreate menjaga import tetap idempotent (sama seperti versi manual)
        $product = Product::updateOrCreate(
            ['name' => $row['name'], 'category_id' => $row['category_id']],
            ['price' => $row['price']],
        );

        $product->wasRecentlyCreated ? $this->created++ : $this->updated++;

        // return null: produk sudah disimpan oleh updateOrCreate.
        // Kalau mengembalikan model baru, library akan menyimpannya lagi (jadi dobel).
        return null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
        ];
    }

    public function chunkSize(): int
    {
        return 500; // baca 500 row per kali, bukan seluruh file sekaligus
    }
}
