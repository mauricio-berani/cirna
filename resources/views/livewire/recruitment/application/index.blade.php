<div>
    <livewire:common.table.header-component :$title :$subtitle :$breadcrumbs />

    <x-card class="shadow bg-base-300">
        <livewire:common.table.actions-component :$searching wire:model="search" :$createRoute />

        {{-- Filtro por área --}}
        <div class="px-4 pb-4 -mt-2">
            <div class="w-full sm:max-w-xs">
                <x-select
                    label="{{ __('fields.area') }}"
                    wire:model.live="area"
                    :options="$areaOptions"
                    option-value="value"
                    option-label="label"
                    class="!outline-none bg-base-100 rounded-lg w-full"
                />
            </div>
        </div>

        <x-table
            :headers="$headers"
            :rows="$this->items"
            :sort-by="$sortBy"
            :per-page="$perPage"
            :per-page-values="$perPageValues"
            link="{{ url('/candidaturas/{id}') }}"
            :no-headers="$noContent"
            class="[&_td]:py-3 [&_th]:py-3"
            with-pagination
        >
            @scope('cell_area', $item)
                <x-badge :value="$item->areaLabel()" class="badge-ghost" />
            @endscope

            @scope('cell_created_at', $item)
                {{ $item->created_at?->format('d/m/Y H:i') }}
            @endscope

            @scope('actions', $item)
                <div class="flex items-center gap-1">
                    <x-button icon="o-eye" class="text-primary btn-ghost btn-circle btn-sm"
                        tooltip="{{ __('interface.view_button') }}"
                        link="{{ route('candidaturas.show', ['itemId' => $item->id]) }}" wire:navigate spinner />

                    <x-button icon="o-arrow-down-tray" class="text-success btn-ghost btn-circle btn-sm"
                        tooltip="{{ __('interface.download_button') }}"
                        wire:click="download('{{ $item->id }}')" spinner="download('{{ $item->id }}')" />
                </div>
            @endscope

            <x-slot:empty>
                <div class="w-full py-6">
                    <livewire:common.table.no-content-component />
                </div>
            </x-slot:empty>
        </x-table>
    </x-card>
</div>
