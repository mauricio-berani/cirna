<?php

namespace App\Livewire\Auth;

use App\Contracts\Auth\AuthenticatesUsers;
use App\Livewire\Forms\Auth\LoginForm;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('layouts::empty')]
#[Title('Login')]
class Login extends Component
{
    use Toast;

    public LoginForm $form;

    protected AuthenticatesUsers $authenticateUserService;

    public function boot(AuthenticatesUsers $authenticateUserService): void
    {
        $this->authenticateUserService = $authenticateUserService;
    }

    public function mount(): mixed
    {
        if (Auth::check()) {
            return redirect()->intended(route('dashboard'));
        }

        return null;
    }

    public function login(): void
    {
        $result = $this->authenticateUserService->handle(
            $this->form->credentials(),
            (string) request()->ip()
        );

        if ($result->throttled) {
            $this->error(trans('auth.throttle', [
                'seconds' => $result->availableIn,
            ]), position: 'toast-top toast-center');

            return;
        }

        if (! $result->successful && ! $result->twoFactorRequired) {
            $this->error(trans('auth.failed'), position: 'toast-top toast-center');

            return;
        }

        if ($result->twoFactorRequired) {
            $this->redirect(route('two-factor.challenge'));

            return;
        }

        $this->success(
            title: __('auth.welcome', ['name' => logged_user()->name]),
            position: 'toast-top',
            timeout: 5000,
            redirectTo: route('dashboard')
        );
    }

    public function render(): View
    {
        return view('livewire.auth.login');
    }
}
