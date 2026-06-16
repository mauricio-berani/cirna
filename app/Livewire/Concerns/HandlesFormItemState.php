<?php

namespace App\Livewire\Concerns;

use App\Enums\Common\UserActions;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Locked;

trait HandlesFormItemState
{
    protected string $action = UserActions::CREATE->value;

    #[Locked]
    public ?Model $item = null;

    #[Locked]
    public bool $creating = true;

    #[Locked]
    public bool $updating = false;

    abstract protected function getModelClass(): string;

    protected function setItem(Model|string|null $item = null): void
    {
        if (! $item) {
            return;
        }

        $this->item = $item instanceof Model
            ? $item
            : $this->resolveItem($item);

        $this->action = UserActions::UPDATE->value;
        $this->creating = false;
        $this->updating = true;
        $this->afterSetItem();
    }

    protected function resolveItem(string $itemId): Model
    {
        $tableName = $this->getModelClass()::TABLE;
        $tablePK = $this->getModelClass()::FIELD_ID;

        return $this->getModelClass()::where(sprintf('%s.%s', $tableName, $tablePK), $itemId)->firstOrFail();
    }

    public function afterSetItem(): void {}
}
