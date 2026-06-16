<?php

namespace App\Livewire\Concerns;

use App\Enums\Common\ComponentEvents;
use App\Enums\Common\UserActions;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Throwable;

trait HandlesIndexDeletion
{
    public string $action = '';

    public string $itemModalText = '';

    public bool $modal = false;

    public ?string $modalText = null;

    #[Locked]
    public ?string $selectedItemId = null;

    abstract protected function getModelClass(): string;

    abstract protected function deleteItem(Model $item): void;

    protected function setItemModalText(string $text): void
    {
        $this->itemModalText = $text;
    }

    public function confirmAction(string $identification, string $itemId, string $action = 'delete'): void
    {
        $this->selectedItemId = $itemId;
        $this->modalText = $this->buildModalText($identification, $action);
        $this->action = "{$action}-item";
        $this->modal = true;

        $this->dispatch(
            ComponentEvents::ACTION_REQUIRED->value,
            modal: $this->modal,
            modalText: $this->modalText,
            action: $this->action,
        );
    }

    protected function buildModalText(string $identification, string $action): string
    {
        return __(
            "action.confirm_{$action}",
            ['type' => $this->itemModalText, 'identification' => $identification]
        );
    }

    protected function canDelete(Model $item): bool
    {
        $this->authorize(UserActions::DELETE->value, $item);

        return true;
    }

    #[On(ComponentEvents::DELETE_ITEM->value)]
    public function delete(): void
    {
        DB::beginTransaction();

        try {
            $item = $this->resolveSelectedItem();

            if (! $this->canDelete($item)) {
                throw new Exception;
            }

            $this->deleteItem($item);

            DB::commit();

            $this->selectedItemId = null;
            $this->toastSuccess(__('feedback.delete_success'));
        } catch (Throwable $error) {
            DB::rollBack();
            logger()->error($error->getMessage());
            $this->toastError(__('feedback.delete_error'));
        }
    }

    protected function resolveSelectedItem(): Model
    {
        return $this->getModelClass()::findOrFail($this->selectedItemId);
    }
}
