<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id'=>Customer::inRandomOrder()->first()->id,
            'product_id'=>Product::inRandomOrder()->first()->id,
            'quantity'=>$this->faker->numberBetween(1,10000),
            'total_amount'=>$this->faker->numberBetween(10000,1000000),
        ];
    }
}
