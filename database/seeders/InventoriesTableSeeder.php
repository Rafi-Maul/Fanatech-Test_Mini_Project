<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class InventoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 50; $i++) {
            $code = strtoupper(Str::random(3)) . rand(100, 999);
            $price = 'Rp ' . number_format(rand(10000, 1000000), 0, ',', '.');
            $stock = rand(1, 100);
            
            Inventory::create([
                'code' => $code,
                'name' => 'Product ' . $i,
                'price' => $price,
                'stock' => $stock,
            ]);
        }
    }
}
