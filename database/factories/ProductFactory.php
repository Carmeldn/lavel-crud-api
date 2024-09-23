<?php

namespace Database\Factories;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\UniqueConstraintViolationException ;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [ 
            'category_id' => Category::inRandomOrder()->first()->id,
            'name'=>$this->faker->unique()->randomElement(['TechGadget','EcoClean','SmartWear','FreshBite','PowerBoost',
            'AquaPure','FitTrack','GlowSkin','HomeComfort','QuickFix']),
            'quantity'=>$this->faker->numberBetween(1,10000),
            'price'=>$this->faker->numberBetween(100,100000)
        ];
    }
}