<?php

namespace App\Livewire\Recruitment\Application;

use App\Livewire\BaseComponent;
use App\Models\Recruitment\Application as ApplicationModel;
use App\Traits\ManagesFilesTrait;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;

#[Layout('layouts::app')]
#[Title('Candidatura')]
class ShowComponent extends BaseComponent
{
    use ManagesFilesTrait;

    public string $filePath = 'applications';

    #[Locked]
    public ApplicationModel $item;

    public string $title = '';

    protected function getModelClass(): string
    {
        return ApplicationModel::class;
    }

    protected function getRoutePrefix(): string
    {
        return 'candidaturas';
    }

    protected function getViewPath(): string
    {
        return 'livewire.recruitment.application.show';
    }

    public function mount(string $itemId): void
    {
        $this->authorize('read', ApplicationModel::class);

        $this->item = ApplicationModel::findOrFail($itemId);
        $this->title = $this->item->name;
        $this->breadcrumbs = [
            ['title' => __('interface.identification.applications.title'), 'link' => route('candidaturas.index')],
            ['title' => $this->item->name],
        ];
    }

    public function download(): mixed
    {
        $this->authorize('read', ApplicationModel::class);

        $url = $this->getFileUrl($this->item->resume_path);

        if (! $url) {
            $this->error(__('site.careers.feedback.error'), position: 'toast-top');

            return null;
        }

        return $this->redirect($url);
    }

    public function render(): View
    {
        return view('livewire.recruitment.application.show', [
            'item' => $this->item,
            'areaLabel' => $this->item->areaLabel(),
            'breadcrumbs' => $this->breadcrumbs,
        ]);
    }
}
