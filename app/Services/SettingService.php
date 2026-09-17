<?php

namespace App\Services;

use App\Models\Setting;
use App\Repositories\Contracts\SettingRepositoryInterface;
use Illuminate\Http\UploadedFile;

class SettingService
{
    private ?Setting $current = null;

    private bool $resolved = false;

    public function __construct(private SettingRepositoryInterface $settings)
    {
    }

    public function current(): ?Setting
    {
        if (!$this->resolved) {
            $this->current = $this->settings->current();
            $this->resolved = true;
        }

        return $this->current;
    }

    public function update(array $data, ?UploadedFile $logo = null): Setting
    {
        if ($logo) {
            $current = $this->settings->current();

            if ($current && $current->logo && file_exists(public_path($current->logo))) {
                unlink(public_path($current->logo));
            }

            $logo->move(public_path('assets/img/logo-login'), 'logo.png');
            $data['logo'] = 'assets/img/logo-login/logo.png';
        }

        $this->current = $this->settings->update($data);
        $this->resolved = true;

        return $this->current;
    }
}
