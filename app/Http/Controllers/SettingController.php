<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingRequest;
use App\Services\SettingService;

class SettingController extends Controller
{
    public function index(SettingService $settingService)
    {
        $settings = $settingService->all();
        return view('settings.index', compact('settings'));
    }

    public function update(UpdateSettingRequest $request, SettingService $settingService)
    {
        $data = $request->validated();
        unset($data['logo']);

        $settingService->update($data, $request->file('logo'));

        return back()->with('success', 'Settings updated.');
    }
}
