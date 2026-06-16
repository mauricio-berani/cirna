<?php

namespace App\Livewire\Common\Settings;

use App\Contracts\Common\UpdatesSettings;
use App\Livewire\BaseComponent;
use App\Livewire\Forms\Common\SettingsForm;
use App\Models\Common\Setting;
use App\Traits\ManagesFilesTrait;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Throwable;

#[Layout('layouts::app')]
#[Title('Configurações')]
class UpdateComponent extends BaseComponent
{
    use ManagesFilesTrait;
    use WithFileUploads;

    public SettingsForm $form;

    public string $title = 'Configurações';

    public string $subtitle = 'Gerencie as configurações gerais do sistema.';

    public string $filePath = 'certificates';

    protected UpdatesSettings $updateSettingsService;

    public function boot(UpdatesSettings $updateSettingsService): void
    {
        $this->updateSettingsService = $updateSettingsService;

        // O certificado ISO é restrito a PDF.
        $this->allowedUploadMimeTypes = ['application/pdf'];
        $this->maxUploadSizeBytes = 5 * 1024 * 1024; // 5 MB
    }

    protected function getModelClass(): string
    {
        return Setting::class;
    }

    protected function getRoutePrefix(): string
    {
        return 'settings';
    }

    protected function getViewPath(): string
    {
        return 'livewire.common.settings.update-component';
    }

    public function getBreadcrumbs(): array
    {
        return [
            ['title' => $this->title],
        ];
    }

    public function mount(): void
    {
        $this->authorize('mount', Setting::class);

        $this->form->setModel();
    }

    public function update(): void
    {
        $this->authorize('update', Setting::class);

        $payload = $this->form->payload();

        if ($this->form->certificate) {
            if (! $this->hasValidPdfSignature($this->form->certificate->getRealPath())) {
                $this->addError('form.certificate', __('site.careers.feedback.invalid_pdf'));

                return;
            }

            $payload['iso_certificate_path'] = $this->uploadFile(
                $this->form->certificate,
                Setting::isoCertificatePath(),
                unique: true,
            );
        }

        try {
            $this->updateSettingsService->handle($payload);
            $this->form->certificate = null;
            $this->success(__('feedback.update_success'), position: 'toast-top');
        } catch (Throwable $error) {
            logger()->error($error->getMessage());
            $this->error(__('feedback.update_error'), position: 'toast-top');
        }
    }

    public function render(): View
    {
        return view('livewire.common.settings.update-component', [
            'breadcrumbs' => $this->getBreadcrumbs(),
            'fallbackEmail' => config('client.contact_email'),
            'certificateUrl' => Setting::isoCertificatePath() ? route('site.certificate') : null,
        ]);
    }
}
