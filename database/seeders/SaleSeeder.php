<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Store;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = Store::all();

        foreach ($stores as $store) {
            $cashier = $store->users()->where('role', 'cashier')->first();
            
            if (!$cashier) {
                $this->command->warn("No cashier found for {$store->name}, skipping...");
                continue;
            }

            $this->command->info("Creating sales for {$store->name}...");

            // Create 5-10 sample sales per store
            $salesCount = rand(5, 10);
            
            for ($i = 0; $i < $salesCount; $i++) {
                // Create sale
                $sale = Sale::create([
                    'store_id' => $store->id,
                    'cashier_id' => $cashier->id,
                    'total_amount' => 0, // Will be calculated from items
                    'payment_method' => ['cash', 'card', 'transfer'][rand(0, 2)],
                    'transaction_date' => now()->subDays(rand(0, 30)),
                ]);

                // Add 1-5 random products to this sale
                $itemsCount = rand(1, 5);
                $products = $store->products()->inRandomOrder()->limit($itemsCount)->get();
                
                $totalAmount = 0;
                
                foreach ($products as $product) {
                    $quantity = rand(1, 3);
                    $unitPrice = $product->price;
                    $subtotal = $quantity * $unitPrice;
                    
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ]);
                    
                    $totalAmount += $subtotal;
                }

                // Update sale total
                $sale->update(['total_amount' => $totalAmount]);
            }

            $this->command->info("Created {$salesCount} sales for {$store->name}");
        }

        $this->command->info("\nAll sales created successfully!");
    }
}
