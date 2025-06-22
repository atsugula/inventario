<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::where('name', 'Electrónica')->first();
        $stationery = Category::where('name', 'Papelería')->first();

        Product::create([
            'name' => 'Mouse inalámbrico',
            'description' => 'Mouse ergonómico con conexión Bluetooth',
            'price' => 99.90,
            'stock' => 50,
            'category_id' => $electronics->id,
        ]);

        Product::create([
            'name' => 'Cuaderno rayado',
            'description' => 'Cuaderno tamaño carta de 100 hojas',
            'price' => 5.50,
            'stock' => 200,
            'category_id' => $stationery->id,
        ]);
    }
}
