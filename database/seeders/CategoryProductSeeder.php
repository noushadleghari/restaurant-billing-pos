<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Starters' => ['color' => '#f59e0b', 'items' => [
                ['French Fries', 3.50], ['Chicken Wings', 6.00], ['Spring Rolls', 4.50],
            ]],
            'Main Course' => ['color' => '#ef4444', 'items' => [
                ['Grilled Chicken', 9.50], ['Beef Burger', 8.00], ['Veggie Pasta', 7.50],
            ]],
            'Beverages' => ['color' => '#3b82f6', 'items' => [
                ['Cappuccino', 3.00], ['Fresh Orange Juice', 3.50], ['Iced Tea', 2.50], ['Mineral Water', 1.00],
            ]],
            'Desserts' => ['color' => '#a855f7', 'items' => [
                ['Chocolate Cake', 4.50], ['Ice Cream', 3.00],
            ]],
        ];

        foreach ($data as $catName => $catData) {
            $category = Category::firstOrCreate(
                ['name' => $catName],
                ['slug' => Category::uniqueSlug($catName), 'color' => $catData['color'], 'is_active' => true]
            );

            foreach ($catData['items'] as [$name, $price]) {
                Product::firstOrCreate(
                    ['name' => $name, 'category_id' => $category->id],
                    ['price' => $price, 'is_available' => true]
                );
            }
        }
    }
}
