<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@dazo.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'store_id' => null, // Super admin tidak terikat ke store tertentu
        ]);

        $this->command->info('Super Admin created: admin@dazo.com / password');
    }
}
