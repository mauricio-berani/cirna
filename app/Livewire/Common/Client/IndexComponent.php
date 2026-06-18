<?php

namespace App\Livewire\Common\Client;

use App\Livewire\IndexBaseComponent;
use App\Models\Common\Client as ClientModel;
use App\Traits\ManagesFilesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Livewire\Attributes\Layout;

#[Layout('layouts::app')]
class IndexComponent extends IndexBaseComponent
{
    use ManagesFilesTrait;

    public string $filePath = 'clientes';

    public function boot(): void
    {
        $this->uploadDisk = 'public';
    }

    protected function itemsQuery(): Builder
    {
        return ClientModel::query();
    }

    protected function deleteItem(EloquentModel $item): void
    {
        if ($item->logo && ! str_starts_with($item->logo, 'assets/')) {
            $this->deleteFile($item->logo);
        }

        $item->delete();
    }

    protected function getModelClass(): string
    {
        return ClientModel::class;
    }

    protected function getRoutePrefix(): string
    {
        return 'clientes';
    }

    protected function getViewPath(): string
    {
        return 'livewire.common.client.index';
    }

    public function mount(): void
    {
        parent::mount();
        $this->setSortByColumn(ClientModel::FIELD_NAME);
        $this->setTableHeaders([
            ClientModel::FIELD_NAME => __('fields.name'),
        ]);
        $this->setItemModalText(__('interface.identification.clients.modal_text'));
        $this->setSearchableFields([
            ClientModel::FIELD_NAME,
        ]);
    }
}
