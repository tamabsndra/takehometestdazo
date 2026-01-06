<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->numberBetween(10000, 5000000),
            'sku' => fake()->unique()->bothify('??##??##'),
        ];
    }
}
