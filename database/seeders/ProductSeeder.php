<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productTemplates = [
            ['name' => 'Laptop Asus ROG', 'description' => 'Gaming laptop dengan spesifikasi tinggi', 'price' => 15000000, 'sku' => 'LAP-001'],
            ['name' => 'Mouse Logitech MX Master 3', 'description' => 'Mouse wireless premium', 'price' => 1200000, 'sku' => 'MOU-001'],
            ['name' => 'Keyboard Mechanical RGB', 'description' => 'Keyboard gaming dengan RGB lighting', 'price' => 850000, 'sku' => 'KEY-001'],
            ['name' => 'Monitor LG 27 inch', 'description' => 'Monitor 4K untuk produktivitas', 'price' => 4500000, 'sku' => 'MON-001'],
            ['name' => 'Webcam Logitech C920', 'description' => 'Webcam HD untuk meeting online', 'price' => 1100000, 'sku' => 'WEB-001'],
            ['name' => 'Headset Gaming HyperX', 'description' => 'Headset gaming dengan surround sound', 'price' => 1800000, 'sku' => 'HEA-001'],
            ['name' => 'SSD Samsung 1TB', 'description' => 'SSD NVMe super cepat', 'price' => 1500000, 'sku' => 'SSD-001'],
            ['name' => 'RAM Corsair 16GB', 'description' => 'RAM DDR4 3200MHz', 'price' => 950000, 'sku' => 'RAM-001'],
            ['name' => 'Mousepad Gaming XXL', 'description' => 'Mousepad besar dengan anti-slip', 'price' => 250000, 'sku' => 'PAD-001'],
            ['name' => 'USB Hub 7 Port', 'description' => 'USB Hub dengan fast charging', 'price' => 350000, 'sku' => 'USB-001'],
            ['name' => 'Kabel HDMI 2.1', 'description' => 'Kabel HDMI 4K 60Hz', 'price' => 150000, 'sku' => 'CAB-001'],
            ['name' => 'Laptop Stand Aluminum', 'description' => 'Stand laptop dengan ventilasi', 'price' => 450000, 'sku' => 'STA-001'],
            ['name' => 'Printer Canon Pixma', 'description' => 'Printer inkjet multifungsi', 'price' => 2200000, 'sku' => 'PRI-001'],
            ['name' => 'Speaker Bluetooth JBL', 'description' => 'Speaker portable dengan bass kuat', 'price' => 1500000, 'sku' => 'SPK-001'],
            ['name' => 'Power Bank 20000mAh', 'description' => 'Power bank fast charging', 'price' => 350000, 'sku' => 'POW-001'],
        ];

        $stores = Store::all();

        foreach ($stores as $store) {
            $this->command->info("Creating products for {$store->name}...");
            
            foreach ($productTemplates as $template) {
                Product::create([
                    'store_id' => $store->id,
                    'name' => $template['name'],
                    'description' => $template['description'],
                    'price' => $template['price'] + rand(-100000, 100000), // Variasi harga
                    'sku' => $template['sku'] . '-' . $store->id,
                ]);
            }
            
            $this->command->info("Created " . count($productTemplates) . " products for {$store->name}");
        }

        $this->command->info("\nAll products created successfully!");
    }
}
