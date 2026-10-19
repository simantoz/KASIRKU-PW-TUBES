<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (3 Level Pengguna)
        $owner = User::create([
            'name' => 'Pak Hendra (Owner / Manajer)',
            'email' => 'owner@kasirku.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        $gudang = User::create([
            'name' => 'Budi Santoso (Admin Gudang)',
            'email' => 'gudang@kasirku.com',
            'password' => Hash::make('password'),
            'role' => 'gudang',
            'phone' => '081298765432',
            'is_active' => true,
        ]);

        $kasir1 = User::create([
            'name' => 'Siti Rahma (Kasir 1)',
            'email' => 'kasir@kasirku.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'phone' => '085612345678',
            'is_active' => true,
        ]);

        $kasir2 = User::create([
            'name' => 'Dimas Aditya (Kasir 2)',
            'email' => 'kasir2@kasirku.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'phone' => '085798765432',
            'is_active' => true,
        ]);

        // 2. Seed Categories
        $catMakanan = Category::create([
            'name' => 'Makanan & Mie Instan',
            'slug' => 'makanan-mie-instan',
            'description' => 'Produk mie instan, makanan siap saji, dan bumbu dapur',
        ]);

        $catMinuman = Category::create([
            'name' => 'Minuman & Kopi',
            'slug' => 'minuman-kopi',
            'description' => 'Air mineral, minuman dingin, teh, susu, dan kopi botol',
        ]);

        $catSnack = Category::create([
            'name' => 'Snack & Biskuit',
            'slug' => 'snack-biskuit',
            'description' => 'Camilan keripik, wafer, biskuit, dan cokelat',
        ]);

        $catSembako = Category::create([
            'name' => 'Sembako & Dapur',
            'slug' => 'sembako-dapur',
            'description' => 'Beras, minyak goreng, gula pasir, dan kebutuhan pokok',
        ]);

        $catPerawatan = Category::create([
            'name' => 'Perawatan Diri & Kebersihan',
            'slug' => 'perawatan-diri-kebersihan',
            'description' => 'Sabun mandi, pasta gigi, shampoo, dan deterjen',
        ]);

        // 3. Seed Products
        $productsData = [
            [
                'category_id' => $catMakanan->id,
                'code' => 'MIE001',
                'name' => 'Indomie Goreng Spesial 85g',
                'cost_price' => 2800,
                'selling_price' => 3500,
                'stock' => 120,
                'min_stock' => 15,
                'unit' => 'bks',
            ],
            [
                'category_id' => $catMakanan->id,
                'code' => 'MIE002',
                'name' => 'Indomie Kuah Ayam Bawang 75g',
                'cost_price' => 2700,
                'selling_price' => 3500,
                'stock' => 80,
                'min_stock' => 15,
                'unit' => 'bks',
            ],
            [
                'category_id' => $catMinuman->id,
                'code' => 'MIN001',
                'name' => 'Le Minerale 600ml',
                'cost_price' => 2200,
                'selling_price' => 3500,
                'stock' => 75,
                'min_stock' => 10,
                'unit' => 'btl',
            ],
            [
                'category_id' => $catMinuman->id,
                'code' => 'MIN002',
                'name' => 'Teh Botol Sosro Kotak 250ml',
                'cost_price' => 3200,
                'selling_price' => 4500,
                'stock' => 45,
                'min_stock' => 10,
                'unit' => 'ktk',
            ],
            [
                'category_id' => $catMinuman->id,
                'code' => 'MIN003',
                'name' => 'Ultra Milk Cokelat 250ml',
                'cost_price' => 5200,
                'selling_price' => 7000,
                'stock' => 30,
                'min_stock' => 8,
                'unit' => 'ktk',
            ],
            [
                'category_id' => $catSnack->id,
                'code' => 'SNK001',
                'name' => 'Chitato Sapi Panggang 68g',
                'cost_price' => 8800,
                'selling_price' => 11500,
                'stock' => 25,
                'min_stock' => 5,
                'unit' => 'bks',
            ],
            [
                'category_id' => $catSnack->id,
                'code' => 'SNK002',
                'name' => 'Oreo Vanilla 133g',
                'cost_price' => 7800,
                'selling_price' => 10000,
                'stock' => 22,
                'min_stock' => 5,
                'unit' => 'bks',
            ],
            [
                'category_id' => $catSnack->id,
                'code' => 'SNK003',
                'name' => 'SilverQueen Cashew 58g',
                'cost_price' => 12500,
                'selling_price' => 16500,
                'stock' => 18,
                'min_stock' => 5,
                'unit' => 'pcs',
            ],
            [
                'category_id' => $catSembako->id,
                'code' => 'SMB001',
                'name' => 'Beras Ramos Premium 5kg',
                'cost_price' => 64000,
                'selling_price' => 74500,
                'stock' => 14,
                'min_stock' => 5,
                'unit' => 'sak',
            ],
            [
                'category_id' => $catSembako->id,
                'code' => 'SMB002',
                'name' => 'Minyak Goreng Bimoli Pouch 2L',
                'cost_price' => 32000,
                'selling_price' => 38000,
                'stock' => 4, // Low stock!
                'min_stock' => 6,
                'unit' => 'pch',
            ],
            [
                'category_id' => $catSembako->id,
                'code' => 'SMB003',
                'name' => 'Gulaku Gula Pasir Premium 1kg',
                'cost_price' => 14500,
                'selling_price' => 17500,
                'stock' => 3, // Low stock!
                'min_stock' => 5,
                'unit' => 'bks',
            ],
            [
                'category_id' => $catPerawatan->id,
                'code' => 'PRW001',
                'name' => 'Sabun Mandi Lifebuoy Total 10 85g',
                'cost_price' => 3400,
                'selling_price' => 4500,
                'stock' => 40,
                'min_stock' => 8,
                'unit' => 'pcs',
            ],
            [
                'category_id' => $catPerawatan->id,
                'code' => 'PRW002',
                'name' => 'Pepsodent Fresh Cool 120g',
                'cost_price' => 9500,
                'selling_price' => 13000,
                'stock' => 20,
                'min_stock' => 5,
                'unit' => 'pcs',
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $item) {
            $createdProducts[] = Product::create($item);
        }

        // 4. Seed Stock In (Restock log dari Admin Gudang)
        StockMutation::create([
            'product_id' => $createdProducts[0]->id,
            'user_id' => $gudang->id,
            'type' => 'in',
            'supplier_name' => 'PT Indomarco Adi Prima',
            'qty' => 100,
            'cost_price' => 2800,
            'total_cost' => 280000,
            'notes' => 'Pengiriman berkala stok mie goreng mingguan',
            'created_at' => Carbon::now()->subDays(3),
        ]);

        StockMutation::create([
            'product_id' => $createdProducts[2]->id,
            'user_id' => $gudang->id,
            'type' => 'in',
            'supplier_name' => 'CV Sumber Tirta Alami',
            'qty' => 60,
            'cost_price' => 2200,
            'total_cost' => 132000,
            'notes' => 'Pasokan air mineral Le Minerale kartonan',
            'created_at' => Carbon::now()->subDays(2),
        ]);

        // 5. Seed Historical Transactions (Untuk grafik analitik dan laporan keuangan Owner)
        $pastDates = [
            Carbon::now()->subDays(4),
            Carbon::now()->subDays(3),
            Carbon::now()->subDays(2),
            Carbon::now()->subDays(1),
            Carbon::now(),
        ];

        $invCounter = 1;

        foreach ($pastDates as $idx => $date) {
            // Transaksi 1 pada tanggal tersebut
            $p1 = $createdProducts[0]; // Indomie
            $p2 = $createdProducts[2]; // Le Minerale
            $qty1 = 3;
            $qty2 = 2;
            $subtotal = ($p1->selling_price * $qty1) + ($p2->selling_price * $qty2);
            $total = $subtotal;
            $paid = 20000;
            $change = $paid - $total;

            $invCode = 'KSK-' . $date->format('Ymd') . '-' . str_pad($invCounter++, 4, '0', STR_PAD_LEFT);

            $trx1 = Transaction::create([
                'invoice_code' => $invCode,
                'user_id' => $kasir1->id,
                'customer_name' => 'Pelanggan Umum',
                'subtotal' => $subtotal,
                'discount' => 0,
                'total_amount' => $total,
                'paid_amount' => $paid,
                'change_amount' => $change,
                'payment_method' => 'cash',
                'status' => 'completed',
                'created_at' => $date->copy()->setTime(10, 15, 0),
                'updated_at' => $date->copy()->setTime(10, 15, 0),
            ]);

            TransactionDetail::create([
                'transaction_id' => $trx1->id,
                'product_id' => $p1->id,
                'product_code' => $p1->code,
                'product_name' => $p1->name,
                'cost_price' => $p1->cost_price,
                'selling_price' => $p1->selling_price,
                'qty' => $qty1,
                'subtotal' => $p1->selling_price * $qty1,
                'created_at' => $trx1->created_at,
                'updated_at' => $trx1->updated_at,
            ]);

            TransactionDetail::create([
                'transaction_id' => $trx1->id,
                'product_id' => $p2->id,
                'product_code' => $p2->code,
                'product_name' => $p2->name,
                'cost_price' => $p2->cost_price,
                'selling_price' => $p2->selling_price,
                'qty' => $qty2,
                'subtotal' => $p2->selling_price * $qty2,
                'created_at' => $trx1->created_at,
                'updated_at' => $trx1->updated_at,
            ]);

            // Transaksi 2 pada tanggal tersebut
            $p3 = $createdProducts[5]; // Chitato
            $p4 = $createdProducts[4]; // Ultra Milk
            $qty3 = 1;
            $qty4 = 2;
            $subtotal2 = ($p3->selling_price * $qty3) + ($p4->selling_price * $qty4);
            $total2 = $subtotal2;
            $paid2 = 30000;
            $change2 = $paid2 - $total2;

            $invCode2 = 'KSK-' . $date->format('Ymd') . '-' . str_pad($invCounter++, 4, '0', STR_PAD_LEFT);

            $trx2 = Transaction::create([
                'invoice_code' => $invCode2,
                'user_id' => ($idx % 2 == 0) ? $kasir1->id : $kasir2->id,
                'customer_name' => 'Bpk. Ahmad',
                'subtotal' => $subtotal2,
                'discount' => 0,
                'total_amount' => $total2,
                'paid_amount' => $paid2,
                'change_amount' => $change2,
                'payment_method' => ($idx % 2 == 0) ? 'cash' : 'qris',
                'status' => 'completed',
                'created_at' => $date->copy()->setTime(14, 30, 0),
                'updated_at' => $date->copy()->setTime(14, 30, 0),
            ]);

            TransactionDetail::create([
                'transaction_id' => $trx2->id,
                'product_id' => $p3->id,
                'product_code' => $p3->code,
                'product_name' => $p3->name,
                'cost_price' => $p3->cost_price,
                'selling_price' => $p3->selling_price,
                'qty' => $qty3,
                'subtotal' => $p3->selling_price * $qty3,
                'created_at' => $trx2->created_at,
                'updated_at' => $trx2->updated_at,
            ]);

            TransactionDetail::create([
                'transaction_id' => $trx2->id,
                'product_id' => $p4->id,
                'product_code' => $p4->code,
                'product_name' => $p4->name,
                'cost_price' => $p4->cost_price,
                'selling_price' => $p4->selling_price,
                'qty' => $qty4,
                'subtotal' => $p4->selling_price * $qty4,
                'created_at' => $trx2->created_at,
                'updated_at' => $trx2->updated_at,
            ]);
        }
    }
}
