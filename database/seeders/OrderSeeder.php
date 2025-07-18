<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class OrderSeeder extends Seeder
{
    public function run()
    {
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
