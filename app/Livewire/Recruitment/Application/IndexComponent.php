<?php

namespace App\Livewire\Recruitment\Application;

use App\Enums\Recruitment\ApplicationArea;
use App\Livewire\IndexBaseComponent;
use App\Models\Recruitment\Application as ApplicationModel;
use App\Traits\ManagesFilesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

#[Layout('layouts::app')]
class IndexComponent extends IndexBaseComponent
{
    use ManagesFilesTrait;

    public string $filePath = 'applications';

    #[Url(as: 'area', except: '', history: true)]
    public string $area = '';

    protected function itemsQuery(): Builder
    {
        return ApplicationModel::query();
    }

    protected function deleteItem(EloquentModel $item): void
    {
        $item->delete();
    }

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
        return 'livewire.recruitment.application.index';
    }

    public function mount(): void
    {
        parent::mount();
        $this->setSortByColumn(ApplicationModel::FIELD_CREATED_AT);
        $this->sortBy['direction'] = 'desc';
        $this->setTableHeaders([
            ApplicationModel::FIELD_NAME => __('fields.name'),
            ApplicationModel::FIELD_EMAIL => __('fields.email'),
            ApplicationModel::FIELD_AREA => __('fields.area'),
            ApplicationModel::FIELD_CREATED_AT => __('fields.created_at'),
        ]);
        $this->setSearchableFields([
            ApplicationModel::FIELD_NAME,
            ApplicationModel::FIELD_EMAIL,
        ]);
    }

    public function updatedArea(): void
    {
        $this->resetPage();
    }

    protected function getModelWhere(): ?array
    {
        if ($this->area === '') {
            return null;
        }

        return [
            [ApplicationModel::FIELD_AREA, '=', $this->area],
        ];
    }

    /**
     * Redireciona para a URL assinada temporária do currículo (download seguro).
     */
    public function download(string $itemId): mixed
    {
        $this->authorize('read', ApplicationModel::class);

        /** @var ApplicationModel $application */
        $application = ApplicationModel::findOrFail($itemId);

        $url = $this->getFileUrl($application->resume_path);

        if (! $url) {
            $this->error(__('site.careers.feedback.error'), position: 'toast-top');

            return null;
        }

        return $this->redirect($url);
    }

    public function setCustomViewData(): array
    {
        return [
            'areaOptions' => ApplicationArea::options(),
            'area' => $this->area,
        ];
    }

    /**
     * Candidaturas não possuem cadastro pelo painel — oculta o botão "Cadastrar".
     */
    protected function setIndexViewData(): array
    {
        $noContent = $this->items->isEmpty();

        $baseData = [
            'headers' => $this->tableHeaders,
            'noContent' => $noContent,
            'searching' => ! $noContent || ($noContent && $this->search),
            'createRoute' => null,
            'perPage' => $this->perPage,
            'perPageValues' => $this->perPageValues,
        ];

        return array_merge(
            $this->setBaseViewData($baseData),
            $this->setCustomViewData()
        );
    }
}
