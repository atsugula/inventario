<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => 'Electrónica',
            'description' => 'Dispositivos electrónicos y accesorios',
        ]);

        Category::create([
            'name' => 'Papelería',
            'description' => 'Artículos de oficina y útiles escolares',
        ]);
    }
}
