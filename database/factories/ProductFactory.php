<?php

namespace Database\Factories;

use App\Models\Product; // Make sure it's linked to your model
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Here we define default values for fields.
        // The seeder will override these, but it's good practice to have them.
        return [
            'description' => $this->faker->sentence(),
            'reorder_level' => $this->faker->numberBetween(10, 50),
            // We don't need to define every column here, only those
            // that should have a sensible "random" default value.
            // Fields like name, sku, price, etc., will be provided
            // by our seeder.
        ];
    }
}