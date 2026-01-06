<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'cashier_id' => User::factory(),
            'total_amount' => fake()->numberBetween(10000, 1000000),
            'payment_method' => fake()->randomElement(['cash', 'card', 'transfer']),
            'transaction_date' => now(),
        ];
    }
}
