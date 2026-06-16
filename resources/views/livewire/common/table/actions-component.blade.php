<div class="px-4 pt-4 pb-4" x-data="{ expanded: false }">
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between mb-4">
        <div class="flex items-center gap-4 w-full">
            <h3 class="font-bold text-lg whitespace-nowrap">{{ __('Filtros') }}</h3>

            <x-button icon="o-adjustments-horizontal"
                      class="btn-ghost"
                      @click="expanded = !expanded"
                      tooltip="{{ __('table.advanced_filters') }}" />
        </div>
    </div>

    <div x-show="expanded" x-collapse>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-end bg-base-200/50 p-4 rounded-xl mb-4">
            <div class="col-span-1" wire:model="searching">
                <x-input placeholder="{{ __('table.filter_placeholder') }}"
                         wire:model="search"
                         wire:keydown.enter="executeSearch"
                         icon="o-magnifying-glass"
                         class="!outline-none bg-base-100 rounded-lg w-full" />
            </div>

            @if ($statusFilterEnabled)
                <div class="col-span-1">
                    <x-select
                        class="!outline-none bg-base-100 rounded-lg w-full"
                        label="{{ __('fields.status') }}"
                        wire:model="status"
                        :options="$statusOptions"
                        option-label="label"
                        option-value="value"
                        error-class="text-error"
                    />
                </div>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row justify-end gap-3 mb-4">
            <x-button label="{{ __('table.apply_filters') }}"
                      class="btn-primary text-white w-full sm:w-auto px-8"
                      wire:click="executeSearch"
                      spinner />
            <x-button label="{{ __('table.clear_filters') }}"
                      class="btn-error text-white w-full sm:w-auto px-8"
                      wire:click="clearSearch"
                      spinner />
        </div>
    </div>

    <div class="divider"></div>

    @if ($createRoute)
        <div class="flex sm:justify-end mt-4 mb-4">
            <x-button label="{{ __('interface.create_button') }}"
                      class="btn-primary text-white w-full sm:w-auto"
                      wire:navigate
                      link="{{ $createRoute }}" />
        </div>
    @endif
</div>
