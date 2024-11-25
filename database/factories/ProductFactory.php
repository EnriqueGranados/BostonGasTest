<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    // Definir el modelo relacionado
    protected $model = Product::class;

    // Definir el método 'definition'
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->numberBetween(1, 20),
            'stock' => $this->faker->numberBetween(1, 100),
            'image' => $this->faker->imageUrl(640, 480, 'products', true), // Imagen aleatoria
            'category' => $this->faker->word,
        ];
    }
}