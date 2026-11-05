<?php

namespace App\Jobs;

use App\Models\ImportError;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProcessProductImport implements ShouldQueue
{
    use Queueable;
    public int $tries = 3;
    public array $backoff = [10, 60];
    public int $timeout = 60;
    public function __construct(private string $csvPath) {}

    public function handle(): void
    {
        ImportError::truncate();

        $lines = file(Storage::path($this->csvPath), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $rows = array_map('str_getcsv', $lines);
        $header = array_shift($rows);

        $rowNumber = 1;

        foreach ($rows as $row) {
            $rowNumber++;

            if (count($row) !== count($header)) {
                $message = 'Jumlah kolom tidak sesuai dengan header (' . count($row) . ' kolom, diharapkan ' . count($header) . ').';
                Log::warning("Import CSV gagal pada baris {$rowNumber}: {$message}");
                ImportError::create([
                    'row' => $rowNumber,
                    'message' => $message,
                    'raw_data' => implode(',', $row),
                ]);
                continue;
            }

            $validator = Validator::make(array_combine($header, $row), [
                'name' => ['required', 'string'],
                'category_id' => ['required', 'exists:categories,id'],
                'price' => ['required', 'integer', 'min:0'],
            ]);

            if ($validator->fails()) {
                $message = implode(', ', $validator->errors()->all());
                Log::warning("Import CSV gagal pada baris {$rowNumber}: {$message}");
                ImportError::create([
                    'row' => $rowNumber,
                    'message' => $message,
                    'raw_data' => implode(',', $row),
                ]);
                continue;
            }

            $data = $validator->validated();

            Product::updateOrCreate(
                ['name' => $data['name'], 'category_id' => $data['category_id']],
                ['price' => $data['price']],
            );
        }

        Storage::delete($this->csvPath);
    }
}
