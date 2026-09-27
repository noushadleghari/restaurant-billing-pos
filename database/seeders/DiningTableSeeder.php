<?php

namespace Database\Seeders;

use App\Models\DiningTable;
use Illuminate\Database\Seeder;

class DiningTableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DiningTable::firstOrCreate(
                ['name' => 'T'.$i],
                ['capacity' => $i % 3 == 0 ? 6 : 4, 'status' => 'available', 'is_active' => true]
            );
        }
    }
}
