<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Manzana',
                'description' => 'Manzana fresca y crujiente.',
                'price' => 1.50,
                'stock' => 100,
                'image' => '1729323756.jpg', // Asegúrate de que la imagen esté en la carpeta correcta
                'category' => 'Frutas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Plátano',
                'description' => 'Plátanos maduros y dulces.',
                'price' => 1.20,
                'stock' => 150,
                'image' => '1729323756.jpg',
                'category' => 'Frutas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Leche',
                'description' => 'Leche fresca entera.',
                'price' => 0.99,
                'stock' => 50,
                'image' => '1729323756.jpg',
                'category' => 'Lácteos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pan',
                'description' => 'Pan recién horneado.',
                'price' => 0.80,
                'stock' => 80,
                'image' => '1729323756.jpg',
                'category' => 'Panadería',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Huevos',
                'description' => 'Huevos frescos de gallina.',
                'price' => 2.50,
                'stock' => 60,
                'image' => '1729323756.jpg',
                'category' => 'Huevos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
