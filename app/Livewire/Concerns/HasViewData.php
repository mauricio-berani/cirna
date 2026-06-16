<?php

namespace App\Livewire\Concerns;

trait HasViewData
{
    protected function setBaseViewData(array $extraData = []): array
    {
        return array_merge([
            'breadcrumbs' => $this->breadcrumbs,
        ], $extraData);
    }

    public function setCustomViewData(): array
    {
        return [];
    }
}
