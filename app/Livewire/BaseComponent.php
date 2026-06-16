<?php

namespace App\Livewire;

use App\Livewire\Concerns\HasBreadcrumbs;
use App\Livewire\Concerns\HasComponentFeedback;
use App\Livewire\Concerns\HasComponentTranslations;
use App\Livewire\Concerns\HasViewData;
use Livewire\Component;

abstract class BaseComponent extends Component
{
    use HasBreadcrumbs;
    use HasComponentFeedback;
    use HasComponentTranslations;
    use HasViewData;

    public string $title = '';

    abstract protected function getModelClass(): string;

    abstract protected function getRoutePrefix(): string;

    abstract protected function getViewPath(): string;

    protected function getIndexTitle(): string
    {
        return $this->title;
    }
}
