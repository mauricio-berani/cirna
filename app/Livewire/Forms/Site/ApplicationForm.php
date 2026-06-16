<?php

namespace App\Livewire\Forms\Site;

use App\Enums\Recruitment\ApplicationArea;
use Illuminate\Validation\Rules\Enum;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

class ApplicationForm extends Form
{
    public string $name = '';

    public string $email = '';

    public ?string $phone = null;

    public ?string $area = null;

    public ?TemporaryUploadedFile $resume = null;

    /**
     * Honeypot anti-spam.
     */
    public string $website = '';

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:20'],
            'area' => ['required', new Enum(ApplicationArea::class)],
            'resume' => [
                'required',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'extensions:pdf',
                'max:5120', // 5 MB
            ],
            'website' => ['nullable', 'size:0'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'name' => __('fields.name'),
            'email' => __('fields.email'),
            'phone' => __('fields.phone'),
            'area' => __('fields.area'),
            'resume' => __('fields.resume'),
        ];
    }

    /**
     * Valida e retorna apenas os campos escalares (o currículo é tratado pelo componente).
     */
    public function payload(): array
    {
        $validated = $this->validate();

        return [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'area' => $validated['area'],
        ];
    }
}
