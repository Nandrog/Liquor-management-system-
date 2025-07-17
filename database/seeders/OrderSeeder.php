<?php

namespace Database\Seeders;

use App\Models\Order;
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
    }
}
