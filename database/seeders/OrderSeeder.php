<?php

namespace Database\Seeders;

use App\Models\Order;
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

        // Additional seeding logic for 500 orders over the last 12 months
        $statuses = ['pending', 'completed', 'cancelled', 'processing'];
        $paymentStatuses = ['paid', 'unpaid', 'refunded'];

        // Generate orders over the last 12 months
        $startDate = Carbon::now()->subYear();

        // Get all user IDs once
        $userIds = User::pluck('id')->toArray();

        for ($i = 0; $i < 500; $i++) {
            // Random date between startDate and now
            $createdAt = $startDate->copy()->addDays(rand(0, 365))->addHours(rand(0,23))->addMinutes(rand(0,59));

            DB::table('orders')->insert([
                'user_id' => $userIds[array_rand($userIds)],
                'status' => $statuses[array_rand($statuses)],
                'total_amount' => rand(1000, 50000) / 100, // amounts between 10.00 and 500.00
                'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
