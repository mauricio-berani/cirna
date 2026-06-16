<?php

namespace App\Livewire\Common\Table;

use App\Enums\Common\ComponentEvents;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class ActionsComponent extends Component
{
    public bool $searching = false;

    public ?string $createRoute = null;

    #[Modelable]
    public string $search = '';

    public bool $statusFilterEnabled = false;

    public array $statusOptions = [];

    public ?string $status = null;

    public function mount(bool $searching, ?string $createRoute = null): void
    {
        $this->searching = $searching;
        $this->createRoute = $createRoute;
    }

    public function executeSearch(): void
    {
        $this->dispatch(ComponentEvents::SEARCH_UPDATED->value, search: $this->search);
        if ($this->statusFilterEnabled) {
            $this->dispatch(ComponentEvents::STATUS_FILTER_UPDATED->value, status: $this->status);
        }
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->status = null;
        $this->dispatch(ComponentEvents::SEARCH_UPDATED->value, search: $this->search);
        if ($this->statusFilterEnabled) {
            $this->dispatch(ComponentEvents::STATUS_FILTER_UPDATED->value, status: $this->status);
        }
    }

    public function render(): View
    {
        return view('livewire.common.table.actions-component');
    }
}
