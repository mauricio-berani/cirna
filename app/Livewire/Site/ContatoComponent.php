<?php

namespace App\Livewire\Site;

use App\Contracts\Site\SendsContactMessages;
use App\Enums\Site\ContactSector;
use App\Livewire\Forms\Site\ContactForm;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

#[Layout('layouts::public')]
class ContatoComponent extends Component
{
    use Toast;

    public ContactForm $form;

    protected SendsContactMessages $sendContactMessageService;

    public function boot(SendsContactMessages $sendContactMessageService): void
    {
        $this->sendContactMessageService = $sendContactMessageService;
    }

    public function send(): void
    {
        $key = 'contact-form:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, maxAttempts: 5)) {
            $this->error(
                __('site.contact.feedback.throttle', ['seconds' => RateLimiter::availableIn($key)]),
                position: 'toast-top toast-center'
            );

            return;
        }

        $payload = $this->form->payload();

        try {
            $this->sendContactMessageService->handle($payload);
            RateLimiter::hit($key, decaySeconds: 600);

            $this->form->reset();
            $this->success(__('site.contact.feedback.success'), position: 'toast-top toast-center', timeout: 6000);
        } catch (Throwable $e) {
            logger()->error('Falha ao enviar contato do site: '.$e->getMessage());
            $this->error(__('site.contact.feedback.error'), position: 'toast-top toast-center');
        }
    }

    #[Title('Contato')]
    public function render(): View
    {
        return view('livewire.site.contato', [
            'sectorOptions' => ContactSector::options(),
        ]);
    }
}
