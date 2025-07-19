<?php
/*
namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
//use Carbon\Carbon;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() == 0) {
            $this->command->warn('No users found. Please seed users first.');
            return;
        }

        // Supplier is nullable, so no error if none exists
        $suppliers = Supplier::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();

        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed'];

        for ($i = 1; $i <= 15; $i++) {
            $supplierId = count($suppliers) > 0 ? $suppliers[array_rand($suppliers)] : null;
            $userId = $users[array_rand($users)];

            Order::create([
                'supplier_id'     => $supplierId,
                'type'            => ['purchase', 'return', 'transfer'][array_rand(['purchase', 'return', 'transfer'])],
                'user_id'         => $userId,
                'status'          => $statuses[array_rand($statuses)],
                'delivered_at'    => rand(0,1) ? Carbon::now()->subDays(rand(0, 10)) : null,
                'paid_at'         => rand(0,1) ? Carbon::now()->subDays(rand(0, 10)) : null,
                'total_amount'    => rand(1000, 50000),
                'payment_status'  => $paymentStatuses[array_rand($paymentStatuses)],
                'transaction_id'  => rand(0,1) ? 'txn_' . Str::random(10) : null,
            ]);
        }

        $this->command->info('✅ Orders seeded!');

        // Additional seeding logic for orders
        $statuses = ['pending', 'completed', 'cancelled', 'processing'];
        $paymentStatuses = ['paid', 'unpaid', 'refunded'];

        // Generate orders
        $baseDate = Carbon::now()->subWeeks(52);

        // Get all user IDs once
        $userIds = User::pluck('id')->toArray();

        for ($i = 0; $i < 52; $i++) {
            $weekDate = (clone $baseDate)->addWeeks($i);

            for($j = 0; $j < rand(3, 10); $j++){
                DB::table('orders')->insert([
                    'user_id' => $userIds[array_rand($userIds)],
                    'status' => $statuses[array_rand($statuses)],
                    'total_amount' => rand(1000, 50000) / 100, // amounts between 10.00 and 500.00
                    'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                    'created_at' => $weekDate->copy()->addDays(rand(0, 6))->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
*/
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() == 0) {
            $this->command->warn('No users found. Please seed users first.');
            return;
        }

        $productIds = Product::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();
        $supplierIds = Supplier::pluck('id')->toArray();

        if (count($productIds) === 0) {
            $this->command->warn('No products found. Please seed products first.');
            return;
        }

        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'unpaid', 'refunded'];

        // 🔹 1. Create 15 random orders
        for ($i = 0; $i < 15; $i++) {
            $order = Order::create([
                'supplier_id'     => count($supplierIds) ? $supplierIds[array_rand($supplierIds)] : null,
                'type'            => ['purchase', 'return', 'transfer'][array_rand([0,1,2])],
                'user_id'         => $userIds[array_rand($userIds)],
                'status'          => $statuses[array_rand($statuses)],
                'delivered_at'    => rand(0,1) ? Carbon::now()->subDays(rand(0, 10)) : null,
                'paid_at'         => rand(0,1) ? Carbon::now()->subDays(rand(0, 10)) : null,
                'total_amount'    => rand(1000, 50000),
                'payment_status'  => $paymentStatuses[array_rand($paymentStatuses)],
                'transaction_id'  => rand(0,1) ? 'txn_' . Str::random(10) : null,
            ]);

            // Add 1–5 random products to each order
            $numItems = rand(1, 5);
            for ($j = 0; $j < $numItems; $j++) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $productIds[array_rand($productIds)],
                    'quantity'   => rand(1, 10),
                    'price'      => rand(1000, 10000) / 100,
                ]);
            }
        }

        // 🔹 2. Create weekly orders for 52 weeks
        $baseDate = Carbon::now()->subWeeks(52);

        for ($i = 0; $i < 52; $i++) {
            $weekDate = (clone $baseDate)->addWeeks($i);

            //vary number of orders per week
            $numOrders = intval(500 +sin($i / 6.0) * 400);

            //$numOrders = rand(3, 10);
            for ($j = 0; $j < $numOrders; $j++) {
                $order = Order::create([
                    'supplier_id'     => count($supplierIds) ? $supplierIds[array_rand($supplierIds)] : null,
                    'type'            => ['purchase', 'return', 'transfer'][array_rand([0,1,2])],
                    'user_id'         => $userIds[array_rand($userIds)],
                    'status'          => $statuses[array_rand($statuses)],
                    'delivered_at'    => rand(0,1) ? $weekDate->copy()->addDays(rand(0, 6)) : null,
                    'paid_at'         => rand(0,1) ? $weekDate->copy()->addDays(rand(0, 6)) : null,
                    'total_amount'    => rand(1000, 50000),
                    'payment_status'  => $paymentStatuses[array_rand($paymentStatuses)],
                    'transaction_id'  => rand(0,1) ? 'txn_' . Str::random(10) : null,
                    'created_at'      => $weekDate->copy()->addDays(rand(0, 6))->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                    'updated_at'      => now(),
                ]);

                // Add 1–4 order items per order
                $numItems = rand(1, 4);
                for ($k = 0; $k < $numItems; $k++) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $productIds[array_rand($productIds)],
                        'quantity'   => rand(1, 8),
                        'price'      => rand(500, 2000) / 100,
                    ]);
                }
            }
        }

        $this->command->info('✅ Orders and order items seeded successfully!');
    }
}
