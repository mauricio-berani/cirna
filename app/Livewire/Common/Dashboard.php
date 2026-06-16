<?php

namespace App\Livewire\Common;

use App\Enums\Auth\Permissions;
use App\Livewire\BaseComponent;
use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts::app')]
class Dashboard extends BaseComponent
{
    protected function getModelClass(): string
    {
        return User::class;
    }

    protected function getRoutePrefix(): string
    {
        return 'dashboard';
    }

    protected function getViewPath(): string
    {
        return 'livewire.common.dashboard';
    }

    public User $user;

    public function mount(): void
    {
        Gate::authorize(Permissions::MOUNT_DASHBOARD->value);

        $this->user = logged_user();
    }

    #[Title('Dashboard')]
    public function render(): View
    {
        return view('livewire.common.dashboard', [
            'user' => $this->user,
            'today' => Carbon::now()->locale('pt_BR')->isoFormat('DD/MM/YYYY'),
        ]);
    }
}
