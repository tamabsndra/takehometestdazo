<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'level' => fake()->randomElement(['pusat', 'cabang', 'retail']),
            'parent_id' => null,
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
        ];
    }
}
