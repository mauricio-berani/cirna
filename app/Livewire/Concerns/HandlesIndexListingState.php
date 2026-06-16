<?php

namespace App\Livewire\Concerns;

use App\Enums\Common\ComponentEvents;
use App\Enums\Common\UserActions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

trait HandlesIndexListingState
{
    #[Url(as: 'pp', except: 10, history: true)]
    public int $perPage = 10;

    public array $perPageValues = [10, 25, 50];

    #[Url(as: 'q', except: '', history: true)]
    public string $search = '';

    public array $searchableFields = [];

    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    public string $subtitle = '';

    public array $tableHeaders = [['key' => 'id', 'label' => 'ID', 'class' => 'w-64']];

    abstract protected function getModelClass(): string;

    abstract protected function itemsQuery(): Builder;

    protected function setSortByColumn(string $columnName): void
    {
        $this->sortBy['column'] = $columnName;
    }

    protected function setTableHeaders(array $definitions): void
    {
        $defaultClass = 'w-4';

        $this->tableHeaders = collect($definitions)
            ->map(function ($config, $field) use ($defaultClass) {
                if (is_string($config)) {
                    return [
                        'key' => $field,
                        'label' => $config,
                        'class' => $defaultClass,
                        'format' => null,
                    ];
                }

                return [
                    'key' => $field,
                    'label' => $config['label'] ?? '',
                    'class' => $config['class'] ?? $defaultClass,
                    'format' => $config['format'] ?? null,
                ];
            })
            ->values()
            ->toArray();
    }

    protected function setSearchableFields(array $searchableFields): void
    {
        $this->searchableFields = $searchableFields;
    }

    #[On(ComponentEvents::SEARCH_UPDATED->value)]
    public function searchUpdated(?string $search = ''): void
    {
        $this->search = $search ?? '';
        $this->resetPage();
    }

    #[Computed]
    public function items(): LengthAwarePaginator
    {
        $this->authorize(UserActions::READ->value, $this->getModelClass());
        $this->sanitizeSortState();

        $baseQuery = $this->itemsQuery();
        $searchTerm = $this->search;
        $searchFields = $this->searchableFields;
        $modelWhere = $this->getModelWhere();

        if ($modelWhere) {
            foreach ($modelWhere as $where) {
                [$column, $operator, $value] = $where;

                if (strtoupper($operator) === 'IN') {
                    $baseQuery->whereIn($column, $value);
                } else {
                    $baseQuery->where($column, $operator, $value);
                }
            }
        }

        if ($searchTerm !== '' && count($searchFields)) {
            $escapedTerm = addcslashes($searchTerm, '%_\\');

            $baseQuery->where(function ($nestedQuery) use ($escapedTerm, $searchFields) {
                collect($searchFields)->each(function ($field) use ($nestedQuery, $escapedTerm) {
                    $nestedQuery->orWhere($field, 'like', '%'.$escapedTerm.'%');
                });
            });
        }

        $baseQuery->orderBy(
            $this->sortBy['column'],
            $this->sortBy['direction']
        );

        return $baseQuery->paginate($this->perPage);
    }

    public function updatedPerPage(int $value): void
    {
        $this->sanitizeState();
        $this->resetPage();
    }

    protected function sanitizeState(): void
    {
        if (! in_array($this->perPage, $this->perPageValues, true)) {
            $this->perPage = $this->perPageValues[0];
        }

        $this->sanitizeSortState();
        $this->search = trim($this->search);
    }

    protected function sanitizeSortState(): void
    {
        $allowedColumns = collect($this->tableHeaders)
            ->pluck('key')
            ->filter()
            ->values()
            ->all();

        if (! in_array($this->sortBy['column'] ?? null, $allowedColumns, true)) {
            $this->sortBy['column'] = $allowedColumns[0] ?? $this->getModelClass()::FIELD_ID;
        }

        $direction = strtolower((string) ($this->sortBy['direction'] ?? 'asc'));

        $this->sortBy['direction'] = in_array($direction, ['asc', 'desc'], true)
            ? $direction
            : 'asc';
    }

    protected function getModelWhere(): ?array
    {
        return null;
    }
}
