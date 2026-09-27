<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'business_name' => 'The Corner Cafe',
            'phone' => '+1 555 123 4567',
            'address' => '221B Coffee Street',
            'currency_symbol' => '$',
            'tax_percent' => '5',
            'receipt_footer' => 'Thank you for visiting! Please come again.',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
