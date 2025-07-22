<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // --- PREPARE DATA ---
        // Get the actors (users, suppliers, customers)
        $procurementOfficer = User::role('Procurement Officer')->first();
        $liquorManager = User::role('Liquor Manager')->first();
        
        // Get Supplier PROFILES, not users
        $suppliers = Supplier::all();
        $customers = Customer::all();

        // Get Products to add to orders
        $rawMaterials = Product::where('type', 'raw_material')->get();
        $finishedGoods = Product::where('type', 'finished_good')->get();

        // --- VALIDATE DATA ---
        if ($suppliers->isEmpty() || $customers->isEmpty() || $rawMaterials->isEmpty() || $finishedGoods->isEmpty()) {
            $this->command->warn('Skipping OrderSeeder due to missing base data (Suppliers, Customers, or Products).');
            return;
        }

        // Use a transaction for safety
        DB::transaction(function () use ($procurementOfficer, $liquorManager, $suppliers, $customers, $rawMaterials, $finishedGoods) {
            // We'll seed 52 supplier orders, 104 customer orders, and 52 vendor orders (one for each week, double for customers)
            $weeks = 52;
            $vendors = \App\Models\Vendor::all();
            for ($i = 0; $i < $weeks; $i++) {
                // Calculate a date for each week
                // Use June to December of the current year for created_at and updated_at
                $year = now()->year;
                $startDate = now()->setDate($year, 6, 1)->startOfDay(); // June 1st
                $endDate = now()->setDate($year, 12, 31)->endOfDay();   // December 31st
                $totalWeeks = $startDate->diffInWeeks($endDate);
                $weekStart = $startDate->copy()->addWeeks($i % $totalWeeks);
                // Ensure weekStart does not go beyond December
                if ($weekStart->greaterThan($endDate)) {
                    $weekStart = $endDate->copy()->subDays(rand(0, 6));
                }
                $createdAt = $weekStart->copy()->addDays(rand(0, 6))->setTime(rand(8, 18), rand(0, 59));
                if ($createdAt->greaterThan($endDate)) {
                    $createdAt = $endDate->copy()->subDays(rand(0, 6))->setTime(rand(8, 18), rand(0, 59));
                }
                $updatedAt = $createdAt->copy()->addDays(rand(0, 6))->setTime(rand(8, 18), rand(0, 59));
                if ($updatedAt->greaterThan($endDate)) {
                    $updatedAt = $endDate->copy()->setTime(rand(8, 18), rand(0, 59));
                }
                // Supplier Orders
                $this->createOrder(
                    $procurementOfficer,
                    OrderType::SUPPLIER_ORDER,
                    OrderStatus::cases()[array_rand(OrderStatus::cases())],
                    ['supplier_id' => $suppliers->random()->id],
                    $rawMaterials->random(rand(1, 3)),
                    $createdAt,
                    $updatedAt
                );
                // Vendor Orders
                $vendor = $vendors->random();
                $user = $vendor->user;
                $this->createOrder(
                    $user,
                    OrderType::VENDOR_ORDER,
                    OrderStatus::cases()[array_rand(OrderStatus::cases())],
                    ['vendor_id' => $vendor->getKey()],
                    $finishedGoods->random(rand(1, 2)),
                    $createdAt,
                    $updatedAt
                );
                // Two customer orders per week
                for ($j = 0; $j < 2; $j++) {
                    $this->createOrder(
                        $liquorManager,
                        OrderType::CUSTOMER_ORDER,
                        OrderStatus::cases()[array_rand(OrderStatus::cases())],
                        ['customer_id' => $customers->random()->id],
                        $finishedGoods->random(rand(1, 2)),
                        $createdAt,
                        $updatedAt
                    );
                }
            }
        });

        $this->command->info('Orders and Order Items seeded successfully!');
    }

    /**
     * A helper function to create a single order with its items.
     */
    private function createOrder(User $creator, OrderType $type, OrderStatus $status, array $attributes, $products, $createdAt = null, $updatedAt = null)
    {
        $orderData = array_merge([
            'user_id' => $creator->getKey(),
            'type' => $type,
            'status' => $status,
            'order_number' => strtoupper($type->value) . '-' . now()->format('Ymd') . '-' . uniqid(),
            'payment_status' => ['pending', 'paid', 'failed'][array_rand(['pending', 'paid', 'failed'])],
        ], $attributes);

        // Add paid_at and delivered_at dates for appropriate statuses
        if (in_array($status, [OrderStatus::PAID, OrderStatus::DELIVERED, OrderStatus::REFUNDED])) {
            // Paid date within the week
            $orderData['paid_at'] = isset($attributes['created_at']) ? $attributes['created_at']->copy()->addDays(rand(0, 6))->setTime(rand(8, 18), rand(0, 59)) : now()->subDays(rand(1, 365))->setTime(rand(8, 18), rand(0, 59));
        }
        if ($status === OrderStatus::DELIVERED) {
            $paidAt = isset($orderData['paid_at']) ? $orderData['paid_at'] : (isset($attributes['created_at']) ? $attributes['created_at'] : now()->subDays(rand(1, 365)));
            $orderData['delivered_at'] = $paidAt->copy()->addDays(rand(0, 6))->setTime(rand(8, 18), rand(0, 59));
        }

        // Use provided created_at and updated_at if available
        $createdAt = $createdAt ?? now();
        $updatedAt = $updatedAt ?? $createdAt;

        $order = Order::create(array_merge($orderData, [
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ]));

        // --- Create Order Items and Calculate Total ---
        $totalAmount = 0;
        foreach ($products as $product) {
            $quantity = rand(5, 50);
            $price = $product->unit_price;
            $subtotal = $quantity * $price;
            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
            ]);
            $totalAmount += $subtotal;
        }

        $order->update(['total_amount' => $totalAmount]);
    }
}