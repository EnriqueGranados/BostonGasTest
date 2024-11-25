<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = \App\Models\Sale::class;

    public function definition(): array
    {
        return [
            'seller' => $this->faker->name(), // Nombre del vendedor
            'customer' => $this->faker->name(), // Nombre del cliente
            'payment' => $this->faker->randomElement(['cash', 'credit', 'debit']), // Método de pago
            'details_sale' => $this->faker->sentence(), // Detalles de la venta
            'total' => $this->faker->randomFloat(2, 10, 250), // Total entre $10 y $200
            'created_at' => now(),
        ];
    }
}
