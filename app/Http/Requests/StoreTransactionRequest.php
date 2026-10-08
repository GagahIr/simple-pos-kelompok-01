<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                foreach ($this->input('items', []) as $index => $item) {
                    $product = Product::find($item['product_id']);

                    if ($product && $item['qty'] > $product->stock) {
                        $validator->errors()->add(
                            'items',
                            "Stok produk {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}, jumlah diminta: {$item['qty']}."
                        );
                    }
                }
            }
        ];
    }
}