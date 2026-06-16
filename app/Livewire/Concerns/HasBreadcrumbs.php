<?php

namespace App\Livewire\Concerns;

trait HasBreadcrumbs
{
    public array $breadcrumbs = [];

    abstract protected function getRoutePrefix(): string;

    abstract protected function getIndexTitle(): string;

    protected function setBreadcrumbs(string $page = 'index'): void
    {
        $indexLink = route("{$this->getRoutePrefix()}.index");

        $this->breadcrumbs = match ($page) {
            'index' => [
                ['title' => $this->title],
            ],
            'create' => [
                ['title' => $this->getIndexTitle(), 'link' => $indexLink],
                ['title' => 'Cadastrar'],
            ],
            'update' => [
                ['title' => $this->getIndexTitle(), 'link' => $indexLink],
                ['title' => 'Atualizar'],
            ],
            default => [
                ['title' => $this->title],
            ],
        };
    }
}
