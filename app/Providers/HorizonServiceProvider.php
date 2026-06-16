<?php

namespace App\Providers;

use App\Enums\Auth\Permissions;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    public function boot(): void
    {
        parent::boot();
    }

    protected function gate(): void
    {
        Gate::define('viewHorizon', function ($user = null) {
            if (app()->environment('local')) {
                return true;
            }

            return $user?->hasPermissionTo(Permissions::VIEW_HORIZON->value) ?? false;
        });
    }
}
