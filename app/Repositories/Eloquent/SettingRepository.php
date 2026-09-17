<?php

namespace App\Repositories\Eloquent;

use App\Models\Setting;
use App\Repositories\Contracts\SettingRepositoryInterface;

class SettingRepository implements SettingRepositoryInterface
{
    public function current(): ?Setting
    {
        return Setting::first();
    }

    public function update(array $data): Setting
    {
        $setting = Setting::firstOrFail();
        $setting->update($data);

        return $setting;
    }
}
