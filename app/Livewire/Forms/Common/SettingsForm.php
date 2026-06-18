<?php

namespace App\Livewire\Forms\Common;

use App\Models\Common\Setting;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

class SettingsForm extends Form
{
    public ?string $careers_email = null;

    public bool $show_clients = false;

    public ?TemporaryUploadedFile $certificate = null;

    public function rules(): array
    {
        return [
            'careers_email' => ['nullable', 'email', 'max:160'],
            'show_clients' => ['boolean'],
            'certificate' => [
                'nullable',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'extensions:pdf',
                'max:5120', // 5 MB
            ],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'careers_email' => __('fields.careers_email'),
            'certificate' => __('fields.iso_certificate'),
        ];
    }

    public function setModel(): void
    {
        $this->careers_email = Setting::get(Setting::KEY_CAREERS_EMAIL);
        $this->show_clients = Setting::showClientsSection();
    }

    /**
     * Valida e retorna os campos escalares (o certificado é tratado pelo componente).
     */
    public function payload(): array
    {
        $validated = $this->validate();

        return [
            'careers_email' => $validated['careers_email'] ?: null,
            'show_clients' => $validated['show_clients'] ? '1' : '0',
        ];
    }
}
