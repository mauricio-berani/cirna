<?php

namespace App\Livewire\Forms\Site;

use App\Enums\Site\ContactSector;
use Illuminate\Validation\Rules\Enum;
use Livewire\Form;

class ContactForm extends Form
{
    public string $name = '';

    public string $email = '';

    public ?string $phone = null;

    public ?string $sector = null;

    public string $message = '';

    /**
     * Honeypot anti-spam: bots preenchem; humanos não veem o campo.
     */
    public string $website = '';

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:20'],
            'sector' => ['nullable', new Enum(ContactSector::class)],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            'website' => ['nullable', 'size:0'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'name' => __('site.contact.fields.name'),
            'email' => __('site.contact.fields.email'),
            'phone' => __('site.contact.fields.phone'),
            'sector' => __('site.contact.fields.sector'),
            'message' => __('site.contact.fields.message'),
        ];
    }

    public function payload(): array
    {
        $validated = $this->validate();

        unset($validated['website']);

        $validated['sector_label'] = $this->sector
            ? ContactSector::from($this->sector)->label()
            : __('site.sectors.general');

        return $validated;
    }
}
