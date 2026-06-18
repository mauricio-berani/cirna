<?php

namespace App\Livewire\Common\Client;

use App\Contracts\Common\CreatesClients;
use App\Contracts\Common\UpdatesClients;
use App\Livewire\FormBaseComponent;
use App\Livewire\Forms\Common\ClientForm;
use App\Models\Common\Client;
use App\Traits\ManagesFilesTrait;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Throwable;

#[Layout('layouts::app')]
class FormComponent extends FormBaseComponent
{
    use ManagesFilesTrait;
    use WithFileUploads;

    public ClientForm $form;

    public string $filePath = 'clientes';

    protected CreatesClients $createClientService;

    protected UpdatesClients $updateClientService;

    public function boot(CreatesClients $createClientService, UpdatesClients $updateClientService): void
    {
        $this->createClientService = $createClientService;
        $this->updateClientService = $updateClientService;
        $this->uploadDisk = 'public';
    }

    protected function getModelClass(): string
    {
        return Client::class;
    }

    protected function getRoutePrefix(): string
    {
        return 'clientes';
    }

    protected function getViewPath(): string
    {
        return 'livewire.common.client.form';
    }

    protected function getUpdateRoute(): ?string
    {
        return route('clientes.update', ['itemId' => $this->item?->getKey()]);
    }

    protected function getIndexTitle(): string
    {
        return __('interface.identification.clients.title');
    }

    public function setCustomViewData(): array
    {
        return [
            'currentLogoUrl' => $this->item instanceof Client ? $this->item->logoUrl() : null,
        ];
    }

    public function afterSetItem(): void
    {
        if ($this->updating && $this->item instanceof Client) {
            $this->form->setModel($this->item);
        }
    }

    public function save(): void
    {
        if ($this->updating) {
            $this->update();

            return;
        }

        $this->create();
    }

    public function create(): void
    {
        $this->authorize('create', $this->getModelClass());
        $payload = $this->form->payload();

        try {
            $payload[Client::FIELD_LOGO] = $this->uploadFile($this->form->logo);

            $this->item = $this->createClientService->handle($payload);
            $this->form->logo = null;
            $this->toastSuccess(__('feedback.create_success'), $this->getUpdateRoute());
        } catch (Throwable $error) {
            logger()->error($error->getMessage());
            $this->toastError(__('feedback.create_error'));
        }
    }

    public function update(): void
    {
        $this->authorize('update', $this->item);
        $payload = $this->form->payload();

        try {
            if ($this->form->logo) {
                $payload[Client::FIELD_LOGO] = $this->uploadFile(
                    $this->form->logo,
                    str_starts_with((string) $this->item->logo, 'assets/') ? null : $this->item->logo,
                    unique: true,
                );
            }

            $this->item = $this->updateClientService->handle($this->item, $payload);
            $this->form->logo = null;
            $this->form->setModel($this->item);
            $this->toastSuccess(__('feedback.update_success'));
        } catch (Throwable $error) {
            logger()->error($error->getMessage());
            $this->toastError(__('feedback.update_error'));
        }
    }
}
