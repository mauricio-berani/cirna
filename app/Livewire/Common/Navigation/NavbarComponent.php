<?php

namespace App\Livewire\Common\Navigation;

use App\Enums\Common\ComponentEvents;
use App\Models\Auth\User;
use App\Traits\ManagesFilesTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class NavbarComponent extends Component
{
    use ManagesFilesTrait;

    public User $user;

    public string $avatarPath;

    public function mount(): void
    {
        $this->user = logged_user();
        $this->avatarPath = $this->getAvatarPath();
    }

    #[On(ComponentEvents::PROFILE_UPDATED->value)]
    public function refreshProfile(): void
    {
        $this->user->refresh();
        $this->avatarPath = $this->getAvatarPath();
    }

    protected function getAvatarPath(): string
    {
        return $this->getFileUrl($this->user->avatar) ?? asset('assets/images/no-avatar.png');
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('login'));
    }

    public function render(): View
    {
        return view('livewire.common.navigation.navbar-component');
    }
}
