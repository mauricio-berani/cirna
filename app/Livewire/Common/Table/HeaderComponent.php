<?php

namespace App\Livewire\Common\Table;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class HeaderComponent extends Component
{
    public string $title;

    public ?string $subtitle = null;

    public array $breadcrumbs = [];

    public function mount(string $title, array $breadcrumbs, ?string $subtitle = null): void
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->breadcrumbs = $breadcrumbs;
    }

    public function render(): View
    {
        return view('livewire.common.table.header-component');
    }
}
