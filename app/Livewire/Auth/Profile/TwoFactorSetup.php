<?php

namespace App\Livewire\Auth\Profile;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

#[Layout('layouts::app')]
#[Title('Configurar 2FA')]
class TwoFactorSetup extends Component
{
    use Toast;

    public string $code = '';

    public string $disableCode = '';

    public ?string $qrCodeSvg = null;

    #[Locked]
    public ?string $secret = null;

    public bool $enabled = false;

    public function mount(): void
    {
        $user = auth()->user();
        $this->enabled = $user->hasTwoFactorEnabled();
    }

    public function generateSecret(): void
    {
        $user = auth()->user();
        $this->secret = Google2FA::generateSecretKey();

        $qrCodeUrl = Google2FA::getQRCodeUrl(
            config('app.name'),
            $user->email,
            $this->secret
        );

        $renderer = new SvgImageBackEnd;
        $writer = new Writer(
            new ImageRenderer(
                new RendererStyle(200),
                $renderer
            )
        );

        $this->qrCodeSvg = $writer->writeString($qrCodeUrl);
    }

    public function enable(): void
    {
        $this->validate([
            'code' => ['required', 'string', 'size:6'],
            'secret' => ['required', 'string'],
        ]);

        if ($this->hasTooManyAttempts('enable')) {
            return;
        }

        $valid = Google2FA::verifyKey($this->secret, $this->code);

        if (! $valid) {
            $this->hitRateLimiter('enable');
            $this->error(__('auth.two_factor_invalid'), position: 'toast-top toast-center');
            $this->code = '';

            return;
        }

        $this->clearRateLimiter('enable');

        $user = auth()->user();
        $user->forceFill([
            'two_factor_secret' => $this->secret,
            'two_factor_confirmed_at' => now(),
        ])->save();

        activity('auth')
            ->performedOn($user)
            ->causedBy($user)
            ->log('2FA ativado');

        $this->enabled = true;
        $this->qrCodeSvg = null;
        $this->secret = null;
        $this->code = '';

        $this->success(__('auth.two_factor_enabled'), position: 'toast-top toast-center');
    }

    public function disable(): void
    {
        $this->validate([
            'disableCode' => ['required', 'string', 'size:6'],
        ]);

        if ($this->hasTooManyAttempts('disable')) {
            return;
        }

        $user = auth()->user();

        if (! Google2FA::verifyKey($user->two_factor_secret, $this->disableCode)) {
            $this->hitRateLimiter('disable');
            $this->error(__('auth.two_factor_invalid'), position: 'toast-top toast-center');
            $this->disableCode = '';

            return;
        }

        $this->clearRateLimiter('disable');

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        activity('auth')
            ->performedOn($user)
            ->causedBy($user)
            ->log('2FA desativado');

        $this->enabled = false;
        $this->disableCode = '';
        session()->forget('two_factor_verified');

        $this->success(__('auth.two_factor_disabled'), position: 'toast-top toast-center');
    }

    public function render(): View
    {
        return view('livewire.auth.profile.two-factor-setup');
    }

    private function hasTooManyAttempts(string $action): bool
    {
        $key = $this->throttleKey($action);

        if (! RateLimiter::tooManyAttempts($key, 5)) {
            return false;
        }

        $this->error(trans('auth.throttle', [
            'seconds' => RateLimiter::availableIn($key),
        ]), position: 'toast-top toast-center');

        return true;
    }

    private function hitRateLimiter(string $action): void
    {
        RateLimiter::hit($this->throttleKey($action), 60);
    }

    private function clearRateLimiter(string $action): void
    {
        RateLimiter::clear($this->throttleKey($action));
    }

    private function throttleKey(string $action): string
    {
        return sprintf(
            'two-factor-%s:%s|%s',
            $action,
            auth()->id(),
            request()->ip()
        );
    }
}
