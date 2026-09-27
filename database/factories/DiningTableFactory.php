<?php

namespace Database\Factories;

use App\Models\DiningTable;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiningTableFactory extends Factory
{
    protected $model = DiningTable::class;

    public function definition(): array
    {
        return [
            'name' => 'T'.fake()->unique()->numberBetween(1, 200),
            'capacity' => fake()->randomElement([2, 4, 6, 8]),
            'status' => 'available',
            'is_active' => true,
        ];
    }
}
