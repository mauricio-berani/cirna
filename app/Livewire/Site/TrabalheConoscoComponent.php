<?php

namespace App\Livewire\Site;

use App\Contracts\Recruitment\CreatesApplications;
use App\Enums\Recruitment\ApplicationArea;
use App\Livewire\Forms\Site\ApplicationForm;
use App\Traits\ManagesFilesTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Throwable;

#[Layout('layouts::public')]
class TrabalheConoscoComponent extends Component
{
    use ManagesFilesTrait;
    use Toast;
    use WithFileUploads;

    public ApplicationForm $form;

    public string $filePath = 'applications';

    protected CreatesApplications $createApplicationService;

    public function boot(CreatesApplications $createApplicationService): void
    {
        $this->createApplicationService = $createApplicationService;

        // Restringe o upload a PDF (defesa em profundidade junto às regras do Form).
        $this->allowedUploadMimeTypes = ['application/pdf'];
        $this->maxUploadSizeBytes = 5 * 1024 * 1024; // 5 MB
    }

    public function send(): void
    {
        $key = 'application-form:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, maxAttempts: 5)) {
            $this->error(
                __('site.careers.feedback.throttle', ['seconds' => RateLimiter::availableIn($key)]),
                position: 'toast-top toast-center'
            );

            return;
        }

        $payload = $this->form->payload();

        if (! $this->hasValidPdfSignature($this->form->resume->getRealPath())) {
            $this->addError('form.resume', __('site.careers.feedback.invalid_pdf'));

            return;
        }

        $resumePath = null;

        try {
            $resumePath = $this->uploadFile($this->form->resume);

            $this->createApplicationService->handle([
                ...$payload,
                'resume_path' => $resumePath,
            ]);

            RateLimiter::hit($key, decaySeconds: 600);

            $this->form->reset();
            $this->success(__('site.careers.feedback.success'), position: 'toast-top toast-center', timeout: 6000);
        } catch (Throwable $error) {
            if ($resumePath) {
                $this->deleteFile($resumePath);
            }

            logger()->error('Falha ao registrar candidatura: '.$error->getMessage());
            $this->error(__('site.careers.feedback.error'), position: 'toast-top toast-center');
        }
    }

    #[Title('Trabalhe Conosco')]
    public function render(): View
    {
        return view('livewire.site.trabalhe-conosco', [
            'areaOptions' => ApplicationArea::options(),
        ]);
    }
}
