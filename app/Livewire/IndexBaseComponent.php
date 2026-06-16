<?php

namespace App\Livewire;

use App\Contracts\View\SupportsPageTitle;
use App\Enums\Common\UserActions;
use App\Livewire\Concerns\HandlesIndexDeletion;
use App\Livewire\Concerns\HandlesIndexListingState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;
use Livewire\WithPagination;

abstract class IndexBaseComponent extends BaseComponent
{
    use HandlesIndexDeletion;
    use HandlesIndexListingState;
    use WithPagination;

    abstract protected function itemsQuery(): Builder;

    abstract protected function deleteItem(Model $item): void;

    public function mount(): void
    {
        $this->authorize(UserActions::MOUNT->value, $this->getModelClass());
        $this->sanitizeState();
        $this->title = $this->mountTranslationPath() ?? $this->getModelClass()::getFrontendTitle();
        $this->subtitle = $this->getModelClass()::getFrontendSubtitle();
        $this->setBreadcrumbs('index');
    }

    protected function setIndexViewData(): array
    {
        $noContent = $this->items->isEmpty();

        $baseData = [
            'headers' => $this->tableHeaders,
            'noContent' => $noContent,
            'searching' => ! $noContent || ($noContent && $this->search),
            'createRoute' => route("{$this->getRoutePrefix()}.create"),
            'perPage' => $this->perPage,
            'perPageValues' => $this->perPageValues,
        ];

        $baseData = $this->setBaseViewData($baseData);
        $customData = $this->setCustomViewData();

        return array_merge($baseData, $customData);
    }

    /**
     * @throws AuthorizationException
     */
    public function render(): View
    {
        /** @var View&SupportsPageTitle $view */
        $view = view(
            $this->getViewPath(),
            $this->setIndexViewData()
        );

        return $view->title($this->title);
    }
}
