<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    // Definir el modelo relacionado
    protected $model = Category::class;

    // Definir el método 'definition'
    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}