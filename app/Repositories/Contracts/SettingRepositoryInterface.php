<?php

namespace App\Repositories\Contracts;

use App\Models\Setting;

interface SettingRepositoryInterface
{
    public function current(): ?Setting;

    public function update(array $data): Setting;
}
