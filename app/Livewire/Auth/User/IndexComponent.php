<?php

namespace App\Livewire\Auth\User;

use App\Livewire\IndexBaseComponent;
use App\Models\Auth\User as UserModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Livewire\Attributes\Layout;

#[Layout('layouts::app')]
class IndexComponent extends IndexBaseComponent
{
    protected function itemsQuery(): Builder
    {
        return UserModel::query();
    }

    protected function deleteItem(EloquentModel $item): void
    {
        $item->delete();
    }

    protected function getModelClass(): string
    {
        return UserModel::class;
    }

    protected function getRoutePrefix(): string
    {
        return 'users';
    }

    protected function getViewPath(): string
    {
        return 'livewire.auth.user.index';
    }

    /**
     * @throws AuthorizationException
     */
    public function mount(): void
    {
        parent::mount();
        $this->setSortByColumn(UserModel::FIELD_NAME);
        $this->setTableHeaders([
            UserModel::FIELD_NAME => __('fields.name'),
            UserModel::FIELD_EMAIL => __('fields.email'),
        ]);
        $this->setItemModalText(__('interface.identification.users.modal_text'));
        $this->setSearchableFields([
            UserModel::FIELD_NAME,
            UserModel::FIELD_EMAIL,
        ]);
    }
}
