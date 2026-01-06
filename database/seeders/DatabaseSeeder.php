<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class,
            StoreSeeder::class,
            ProductSeeder::class,
            SaleSeeder::class,
        ]);
    }
}
