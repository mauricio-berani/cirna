<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

#[Layout('layouts::empty')]
#[Title('Verificação 2FA')]
class TwoFactorChallenge extends Component
{
    use Toast;

    public string $code = '';

    public function mount(): mixed
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (! $user->hasTwoFactorEnabled() || session('two_factor_verified')) {
            return redirect()->route('dashboard');
        }

        return null;
    }

    public function verify(): mixed
    {
        $this->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = Auth::user();

        if ($this->hasTooManyAttempts()) {
            return null;
        }

        $valid = Google2FA::verifyKey($user->two_factor_secret, $this->code);

        if (! $valid) {
            RateLimiter::hit($this->throttleKey(), 60);
            $this->error(__('auth.two_factor_invalid'), position: 'toast-top toast-center');

            $this->code = '';

            return null;
        }

        RateLimiter::clear($this->throttleKey());
        session(['two_factor_verified' => true]);

        activity('auth')
            ->performedOn($user)
            ->causedBy($user)
            ->log('Verificação 2FA concluída');

        return $this->redirect(route('dashboard'));
    }

    public function cancel(): mixed
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function render(): View
    {
        return view('livewire.auth.two-factor-challenge');
    }

    private function hasTooManyAttempts(): bool
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return false;
        }

        $this->error(trans('auth.throttle', [
            'seconds' => RateLimiter::availableIn($this->throttleKey()),
        ]), position: 'toast-top toast-center');

        return true;
    }

    private function throttleKey(): string
    {
        return sprintf(
            'two-factor-challenge:%s|%s',
            Auth::id(),
            request()->ip()
        );
    }
}
