<?php

namespace App\Providers;

use App\Models\Auth\User;
use App\Models\Common\Setting;
use App\Models\Recruitment\Application;
use App\Policies\Auth\UserPolicy;
use App\Policies\Common\SettingPolicy;
use App\Policies\Recruitment\ApplicationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Application::class => ApplicationPolicy::class,
        Setting::class => SettingPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
