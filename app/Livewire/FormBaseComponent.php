<?php

namespace App\Livewire;

use App\Contracts\View\SupportsPageTitle;
use App\Livewire\Concerns\HandlesFormItemState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

abstract class FormBaseComponent extends BaseComponent
{
    use HandlesFormItemState;

    abstract protected function getUpdateRoute(): ?string;

    abstract public function create(): void;

    abstract public function update(): void;

    /**
     * @throws AuthorizationException
     */
    public function mount(Model|string|null $itemId = null): void
    {
        $this->setItem($itemId);
        $this->authorize($this->action, $this->item ?? $this->getModelClass());
        $this->title = $this->mountTranslationPath($this->action) ?? $this->getModelClass()::getFrontendTitle($this->action);
        $this->setBreadcrumbs($this->action);
    }

    /**
     * @throws AuthorizationException
     */
    public function render(): View
    {
        /** @var View&SupportsPageTitle $view */
        $view = view(
            $this->getViewPath(),
            $this->setFormViewData()
        );

        return $view->title($this->title);
    }

    protected function setFormViewData(): array
    {
        $baseData = [
            'item' => $this->item,
        ];

        $baseData = $this->setBaseViewData($baseData);
        $customData = $this->setCustomViewData();

        return array_merge($baseData, $customData);
    }
}
