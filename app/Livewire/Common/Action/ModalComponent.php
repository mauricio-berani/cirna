<?php

namespace App\Livewire\Common\Action;

use App\Enums\Common\ComponentEvents;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalComponent extends Component
{
    public bool $modal = false;

    public ?string $modalText = null;

    public string $action = '';

    #[On(ComponentEvents::ACTION_REQUIRED->value)]
    public function openModal(bool $modal, ?string $modalText = null, string $action = ''): void
    {
        $this->modal = $modal;
        $this->modalText = $modalText;
        $this->action = $action;
    }

    public function confirmAction(): void
    {
        $this->dispatch($this->action);
        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->modal = false;
    }

    public function render(): View
    {
        return view('livewire.common.action.modal-component');
    }
}
