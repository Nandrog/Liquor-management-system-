<?php
/*
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
            
            // --- CREATE 20 SAMPLE SUPPLIER ORDERS (PURCHASE ORDERS) ---
            for ($i = 0; $i < 20; $i++) {
                $this->createOrder(
                    $procurementOfficer, // The user creating the order
                    OrderType::SUPPLIER_ORDER,
                    OrderStatus::cases()[array_rand(OrderStatus::cases())], // Pick a random status from the Enum
                    ['supplier_id' => $suppliers->random()->id], // Assign a random Supplier
                    $rawMaterials->random(rand(1, 3)) // Assign 1 to 3 random raw materials
                );
            }

            // --- CREATE 30 SAMPLE CUSTOMER ORDERS (SALES ORDERS) ---
            for ($i = 0; $i < 30; $i++) {
                $this->createOrder(
                    $liquorManager, // The user creating the order
                    OrderType::CUSTOMER_ORDER,
                    OrderStatus::cases()[array_rand(OrderStatus::cases())], // Pick a random status
                    ['customer_id' => $customers->random()->id], // Assign a random Customer
                    $finishedGoods->random(rand(1, 2)) // Assign 1 to 2 random finished goods
                );
            }
        });

        $this->command->info('Orders and Order Items seeded successfully!');
    }

    /**
     * A helper function to create a single order with its items.
     */
    private function createOrder(User $creator, OrderType $type, OrderStatus $status, array $attributes, $products)
    {
        $orderData = array_merge([
            'user_id' => $creator->id,
            'type' => $type,
            'status' => $status,
            'order_number' => strtoupper($type->value) . '-' . now()->format('Ymd') . '-' . uniqid(),
            'payment_status' => ['pending', 'paid', 'failed'][array_rand(['pending', 'paid', 'failed'])],
        ], $attributes);
        
        // Add paid_at and delivered_at dates for appropriate statuses
        if (in_array($status, [OrderStatus::PAID, OrderStatus::DELIVERED, OrderStatus::REFUNDED])) {
            $orderData['paid_at'] = now()->subDays(rand(1, 30));
        }
        if ($status === OrderStatus::DELIVERED) {
            $orderData['delivered_at'] = now()->subDays(rand(0, 5));
        }

        $order = Order::create($orderData);

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