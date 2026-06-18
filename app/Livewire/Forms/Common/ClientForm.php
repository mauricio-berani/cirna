<?php

namespace App\Livewire\Forms\Common;

use App\Models\Common\Client;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

class ClientForm extends Form
{
    public ?Client $model = null;

    public string $name = '';

    public ?string $url = null;

    public ?TemporaryUploadedFile $logo = null;

    public function rules(): array
    {
        return [
            Client::FIELD_NAME => ['required', 'string', 'min:2', 'max:255'],
            Client::FIELD_URL => ['nullable', 'url', 'max:255'],
            Client::FIELD_LOGO => [
                // No cadastro o logo é obrigatório; na edição é opcional (mantém o atual).
                $this->model ? 'nullable' : 'required',
                'image',
                'mimes:jpeg,png,gif,webp',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'extensions:jpg,jpeg,png,gif,webp',
                'max:5120', // 5 MB
            ],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            Client::FIELD_NAME => __('fields.name'),
            Client::FIELD_URL => __('fields.url'),
            Client::FIELD_LOGO => __('fields.logo'),
        ];
    }

    public function setModel(Client $client): void
    {
        $this->model = $client;
        $this->name = $client->name;
        $this->url = $client->url;
    }

    /**
     * Valida e retorna os campos escalares (o logo é tratado pelo componente).
     */
    public function payload(): array
    {
        $validated = $this->validate();

        return [
            Client::FIELD_NAME => $validated[Client::FIELD_NAME],
            Client::FIELD_URL => $validated[Client::FIELD_URL] ?: null,
        ];
    }
}
