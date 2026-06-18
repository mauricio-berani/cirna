<?php

namespace App\Providers;

use App\Contracts\Auth\AuthenticatesUsers;
use App\Contracts\Auth\BuildsPermissionOptions;
use App\Contracts\Auth\CreatesUsers;
use App\Contracts\Auth\UpdatesProfiles;
use App\Contracts\Auth\UpdatesUsers;
use App\Contracts\Common\CreatesClients;
use App\Contracts\Common\UpdatesClients;
use App\Contracts\Common\UpdatesSettings;
use App\Contracts\Navigation\BuildsSidebarMenus;
use App\Contracts\Recruitment\CreatesApplications;
use App\Contracts\Site\SendsContactMessages;
use App\Services\Auth\AuthenticateUserService;
use App\Services\Auth\BuildPermissionOptionsService;
use App\Services\Auth\CreateUserService;
use App\Services\Auth\UpdateProfileService;
use App\Services\Auth\UpdateUserService;
use App\Services\Common\CreateClientService;
use App\Services\Common\UpdateClientService;
use App\Services\Common\UpdateSettingsService;
use App\Services\Navigation\BuildSidebarMenusService;
use App\Services\Recruitment\CreateApplicationService;
use App\Services\Site\SendContactMessageService;
use App\View\Components\Toast;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->bind(AuthenticatesUsers::class, AuthenticateUserService::class);
        $this->app->bind(CreatesUsers::class, CreateUserService::class);
        $this->app->bind(UpdatesUsers::class, UpdateUserService::class);
        $this->app->bind(BuildsPermissionOptions::class, BuildPermissionOptionsService::class);
        $this->app->bind(UpdatesProfiles::class, UpdateProfileService::class);
        $this->app->bind(BuildsSidebarMenus::class, BuildSidebarMenusService::class);
        $this->app->bind(SendsContactMessages::class, SendContactMessageService::class);
        $this->app->bind(CreatesApplications::class, CreateApplicationService::class);
        $this->app->bind(UpdatesSettings::class, UpdateSettingsService::class);
        $this->app->bind(CreatesClients::class, CreateClientService::class);
        $this->app->bind(UpdatesClients::class, UpdateClientService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::useCspNonce();

        Blade::component('app-toast', Toast::class);

        if ($this->app->isProduction() && str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('global-web', function (Request $request) {
            return Limit::perMinute(120)->by(
                $request->user()?->id ?: $request->ip()
            );
        });
    }
}
