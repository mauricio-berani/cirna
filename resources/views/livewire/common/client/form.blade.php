<div>
    <livewire:common.table.header-component :$title :$breadcrumbs />

    <x-card shadow class="bg-base-100 border border-base-200/50 p-4 sm:p-6 lg:p-8 mt-6">
        <x-form wire:submit.prevent="save" class="max-w-2xl">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-6">
                    <x-input label="{{ __('fields.name') }}" wire:model="form.name"
                        error-class="text-error m-1 p-1"
                        class="focus:outline-hidden focus:ring-1 focus:ring-primary/30 transition-all" />
                </div>

                <div class="col-span-12 md:col-span-6">
                    <x-input label="{{ __('fields.url') }}" wire:model="form.url" type="url"
                        placeholder="https://"
                        error-class="text-error m-1 p-1"
                        class="focus:outline-hidden focus:ring-1 focus:ring-primary/30 transition-all" />
                </div>

                <div class="col-span-12">
                    <hr class="my-2">
                    <h3 class="text-base font-semibold text-base-content">{{ __('fields.logo') }}</h3>
                    <p class="text-sm text-base-content/60 mb-3">{{ __('interface.identification.clients.logo_hint') }}</p>

                    @if ($currentLogoUrl)
                        <div class="inline-flex items-center gap-3 mb-3 rounded-lg bg-base-200 border border-base-200/60 px-4 py-2">
                            <img src="{{ $currentLogoUrl }}" alt="{{ $form->name }}"
                                class="h-10 w-auto max-w-32 object-contain">
                            <span class="text-sm text-base-content/60">{{ __('interface.identification.clients.logo_current') }}</span>
                        </div>
                    @endif

                    <x-file
                        label="{{ __('interface.identification.clients.logo_upload') }}"
                        wire:model="form.logo"
                        accept="image/jpeg,image/png,image/gif,image/webp"
                        hint="{{ __('interface.identification.clients.logo_hint') }}"
                        error-class="text-error m-1 p-1" />

                    <div wire:loading wire:target="form.logo" class="mt-2 text-sm text-base-content/60">
                        <x-icon name="o-arrow-path" class="w-4 h-4 inline animate-spin" />
                        {{ __('site.careers.uploading') }}
                    </div>
                </div>
            </div>

            <x-slot:actions>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <x-button label="{{ __('interface.back_button') }}" link="{{ route('clientes.index') }}" wire:navigate class="btn-secondary text-white w-full sm:w-auto" />
                    @if ($item)
                    <x-button label="{{ __('interface.update_button') }}" class="btn-primary text-white w-full sm:w-auto" type="submit" spinner="save" />
                    @else
                    <x-button label="{{ __('interface.create_button') }}" class="btn-primary text-white w-full sm:w-auto" type="submit" spinner="save" />
                    @endif
                </div>
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
