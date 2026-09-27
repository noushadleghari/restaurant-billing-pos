<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    public function defaults(): array
    {
        return [
            'business_name' => 'My Cafe',
            'phone' => '',
            'address' => '',
            'currency_symbol' => '$',
            'tax_percent' => '0',
            'receipt_footer' => 'Thank you for visiting!',
            'logo' => '',
        ];
    }

    public function all(): array
    {
        return array_merge($this->defaults(), Setting::all_settings());
    }

    public function update(array $data, ?UploadedFile $logo = null): void
    {
        if ($logo) {
            $old = Setting::get('logo');
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            $data['logo'] = $logo->store('logos', 'public');
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
