<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/reports/export/pdf');
        $response->assertRedirect('/login');
    }

    public function test_non_admin_cannot_export_pdf(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->get('/reports/export/pdf');
        $response->assertStatus(403);
    }

    public function test_admin_can_export_pdf_with_top_products(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Minuman']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Kopi Susu Gula Aren',
            'price' => 18000,
            'stock' => 50,
            'is_active' => true,
        ]);

        $transaction = Transaction::create([
            'user_id' => $admin->id,
            'total' => 36000,
        ]);

        TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'product_id' => $product->id,
            'qty' => 2,
            'subtotal' => 36000,
        ]);

        $response = $this->actingAs($admin)->get('/reports/export/pdf');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('laporan-penjualan.pdf', $response->headers->get('content-disposition'));
    }

    public function test_pdf_view_contains_top_products_table(): void
    {
        $item = (object) [
            'id' => 1,
            'name' => 'Kopi Susu Gula Aren',
            'category_name' => 'Minuman',
            'total_qty' => 15,
            'total_revenue' => 270000,
        ];

        $view = $this->view('reports.pdf', [
            'totalPenjualan' => 270000,
            'jumlahTransaksi' => 5,
            'transactions' => collect(),
            'topProducts' => collect([$item]),
            'from' => '2026-11-01',
            'to' => '2026-11-05',
            'category_id' => null,
        ]);

        $view->assertSee('Produk Terlaris');
        $view->assertSee('Nama Produk');
        $view->assertSee('Jumlah Terjual');
        $view->assertSee('Total Penjualan');
        $view->assertSee('Kopi Susu Gula Aren');
        $view->assertSee('Minuman');
        $view->assertSee('15');
        $view->assertSee('Rp 270,000');
    }
}
