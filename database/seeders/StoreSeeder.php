<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Toko Pusat
        $tokoPusat = Store::create([
            'name' => 'Dazo Pusat Jogja',
            'level' => 'pusat',
            'parent_id' => null,
            'address' => 'Jl. Malioboro No. 1, Yogyakarta',
            'phone' => '0274-512345',
        ]);
        $this->command->info("Created: {$tokoPusat->name} (Admin: admin.dazopusatjogja@dazo.com, Kasir: kasir.dazopusatjogja@dazo.com)");

        // Create Toko Cabang 1
        $cabang1 = Store::create([
            'name' => 'Dazo Cabang Sleman',
            'level' => 'cabang',
            'parent_id' => $tokoPusat->id,
            'address' => 'Jl. Magelang KM 5, Sleman',
            'phone' => '0274-867890',
        ]);
        $this->command->info("Created: {$cabang1->name} (Admin: admin.dazocabangsleman@dazo.com, Kasir: kasir.dazocabangsleman@dazo.com)");

        // Create Toko Cabang 2
        $cabang2 = Store::create([
            'name' => 'Dazo Cabang Bantul',
            'level' => 'cabang',
            'parent_id' => $tokoPusat->id,
            'address' => 'Jl. Bantul KM 7, Bantul',
            'phone' => '0274-367890',
        ]);
        $this->command->info("Created: {$cabang2->name} (Admin: admin.dazocabangbantul@dazo.com, Kasir: kasir.dazocabangbantul@dazo.com)");

        // Create Toko Retail 1 (under Cabang Sleman)
        $retail1 = Store::create([
            'name' => 'Dazo Retail Gejayan',
            'level' => 'retail',
            'parent_id' => $cabang1->id,
            'address' => 'Jl. Gejayan No. 45, Sleman',
            'phone' => '0274-556789',
        ]);
        $this->command->info("Created: {$retail1->name} (Admin: admin.dazoretailgejayan@dazo.com, Kasir: kasir.dazoretailgejayan@dazo.com)");

        // Create Toko Retail 2 (under Cabang Sleman)
        $retail2 = Store::create([
            'name' => 'Dazo Retail Jakal',
            'level' => 'retail',
            'parent_id' => $cabang1->id,
            'address' => 'Jl. Kaliurang KM 10, Sleman',
            'phone' => '0274-889012',
        ]);
        $this->command->info("Created: {$retail2->name} (Admin: admin.dazoretailjakal@dazo.com, Kasir: kasir.dazoretailjakal@dazo.com)");

        // Create Toko Retail 3 (under Cabang Bantul)
        $retail3 = Store::create([
            'name' => 'Dazo Retail Parangtritis',
            'level' => 'retail',
            'parent_id' => $cabang2->id,
            'address' => 'Jl. Parangtritis No. 88, Bantul',
            'phone' => '0274-334455',
        ]);
        $this->command->info("Created: {$retail3->name} (Admin: admin.dazoretailparangtritis@dazo.com, Kasir: kasir.dazoretailparangtritis@dazo.com)");

        $this->command->info("\nAll stores created with their admin and cashier accounts!");
        $this->command->info("Default password for all accounts: password");
    }
}
