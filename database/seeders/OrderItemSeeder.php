<?php

namespace Database\Seeders;



use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();
        $products = Product::all();

        if ($orders->isEmpty() || $products->isEmpty()) {
            $this->command->warn('No orders or products found. Please seed them first.');
            return;
        }

        foreach ($orders as $order) {
            $itemsCount = rand(1, 4); // Each order gets 1–4 items

            for ($i = 0; $i < $itemsCount; $i++) {
                $product = $products->random();

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 10),
                    'price' => $product->unit_price,
                ]);
            }
        }

        $this->command->info('✅ OrderItems seeded!');
    }
}

