<div>
    <livewire:common.table.header-component :$title :$subtitle :$breadcrumbs />

    <x-card class="shadow bg-base-300">
        <livewire:common.table.actions-component :$searching wire:model="search" :$createRoute />
        <x-table
            :headers="$headers"
            :rows="$this->items"
            :sort-by="$sortBy"
            :per-page="$perPage"
            :per-page-values="$perPageValues"
            link="{{ url('/users/update/{id}') }}"
            :no-headers="$noContent"
            class="[&_td]:py-3 [&_th]:py-3"
            with-pagination
        >
            @scope('actions', $item)
            <x-button icon="s-pencil" class="text-primary btn-ghost btn-circle btn-sm" tooltip="{{ __('interface.update_button') }}" link="{{ route('users.update', ['itemId' => $item->id]) }}" wire:navigate spinner />

            <x-button icon="s-trash" class="text-error btn-ghost btn-circle btn-sm" tooltip="{{ __('interface.delete_button') }}" wire:click="confirmAction('{{ $item['name'] }}', '{{ $item['id'] }}')" spinner />
            @endscope
            <x-slot:empty>
                <div class="w-full py-6">
                    <livewire:common.table.no-content-component />
                </div>
            </x-slot:empty>
        </x-table>
    </x-card>

    <livewire:common.action.modal-component />

</div>
