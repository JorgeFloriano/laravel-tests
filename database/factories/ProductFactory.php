<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Portuguese product name examples
        $products = [
            'Camiseta', 'Calça Jeans', 'Tênis Esportivo', 'Relógio', 'Óculos de Sol',
            'Mochila', 'Notebook', 'Smartphone', 'Fone de Ouvido', 'Câmera Digital',
            'Perfume', 'Creme Hidratante', 'Sabonete', 'Shampoo', 'Condicionador'
        ];

        return [
            'name' => $this->faker->randomElement($products) . ' ' . $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(nbMaxDecimals: 2, min: 1, max: 1000),
        ];
    }
}