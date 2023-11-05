<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productTypes = [
            ['name' => 'Pastéis Salgados'],
            ['name' => 'Pastéis Doces'],
            ['name' => 'Bebidas'],
        ];

        foreach ($productTypes as $productType) {
            ProductType::create($productType);
        }
    }
}
