<?php

namespace App\Services\Common;

use App\Contracts\Common\UpdatesSettings;
use App\Models\Common\Setting;
use Illuminate\Support\Facades\DB;

class UpdateSettingsService implements UpdatesSettings
{
    /**
     * @param  array<string, string|null>  $payload
     */
    public function handle(array $payload): void
    {
        DB::transaction(function () use ($payload) {
            if (array_key_exists('careers_email', $payload)) {
                Setting::put(Setting::KEY_CAREERS_EMAIL, $payload['careers_email']);
            }

            if (array_key_exists('iso_certificate_path', $payload)) {
                Setting::put(Setting::KEY_ISO_CERTIFICATE, $payload['iso_certificate_path']);
            }
        });
    }
}
