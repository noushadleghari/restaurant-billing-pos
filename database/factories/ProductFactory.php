<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => ucfirst(fake()->unique()->words(2, true)),
            'sku' => strtoupper(fake()->bothify('SKU-####')),
            'price' => fake()->randomFloat(2, 1, 25),
            'description' => fake()->optional()->sentence(),
            'is_available' => true,
        ];
    }
}
