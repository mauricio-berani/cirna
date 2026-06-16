<div>
    @php($fieldColumn = 'col-span-12 md:col-span-4')
    @php($fullColumn = 'col-span-12')
    @php($permissionColumn = 'col-span-12 sm:col-span-6 md:col-span-4 lg:col-span-3')

    <livewire:common.table.header-component :$title :$breadcrumbs />

    <x-card shadow class="bg-base-100 border border-base-200/50 p-4 sm:p-6 lg:p-8 mt-6">
        <x-form wire:submit.prevent="save">
            <x-tabs wire:model="tab">
                <x-tab name="general" label="{{ __('interface.general') }}">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="{{ $fieldColumn }}">
                            <x-input label="{{ __('fields.name') }}" wire:model="form.name" error-class="text-error m-1 p-1" class="focus:outline-hidden focus:ring-1 focus:ring-primary/30 transition-all"/>
                        </div>

                        <div class="{{ $fieldColumn }}">
                            <x-input label="{{ __('fields.email') }}" wire:model="form.email" error-class="text-error m-1 p-1" class="focus:outline-hidden focus:ring-1 focus:ring-primary/30 transition-all"/>
                        </div>

                        <div class="{{ $fieldColumn }}">
                            <x-select label="{{ __('fields.role') }}" :options="$roleOptions" option-value="value" option-label="label" wire:model="form.user_role" class="focus:outline-hidden focus:ring-1 focus:ring-primary/30 transition-all" :disabled="$editingSelf"/>
                        </div>

                        <div class="{{ $fieldColumn }}">
                            <x-input
                                class="focus:outline-hidden focus:ring-1 focus:ring-primary/30 transition-all"
                                label="{{ __('fields.phone') }}"
                                wire:model.live="form.phone"
                                error-class="text-error m-1 p-1"
                                x-data
                                x-mask:dynamic="$input.length > 14 ? '(99) 9 9999-9999' : '(99) 9999-9999'"
                            />
                        </div>

                        <div class="{{ $fieldColumn }}">
                            <x-password label="{{ __('fields.password') }}" wire:model="form.password" error-class="text-error m-1 p-1" class="focus:outline-hidden focus:ring-1 focus:ring-primary/30 transition-all" right />
                        </div>

                        <div class="{{ $fieldColumn }}">
                            <x-password label="{{ __('fields.password_confirmation') }}" wire:model="form.password_confirmation" error-class="text-error m-1 p-1" class="focus:outline-hidden focus:ring-1 focus:ring-primary/30 transition-all" right />
                        </div>
                    </div>
                </x-tab>

                <x-tab name="permissions" label="{{ __('interface.permissions') }}">
                    @if ($editingSelf)
                        <div class="alert alert-warning mb-4">
                            A edição do próprio papel e das próprias permissões deve ser feita por outro administrador.
                        </div>
                    @endif
                    <div class="grid grid-cols-12 gap-6">
                        @foreach ($permissionOptions as $group)
                            <div class="{{ $fullColumn }}">
                                <div class="text-sm font-semibold text-base-content/70 mb-3">
                                    {{ $group['group'] }}
                                </div>
                                <div class="grid grid-cols-12 gap-4">
                                    @foreach ($group['items'] as $permission)
                                        <div class="{{ $permissionColumn }}">
                                            <x-checkbox
                                                label="{{ $permission['label'] }}"
                                                value="{{ $permission['value'] }}"
                                                wire:model="form.permissions"
                                                :disabled="$editingSelf"
                                            />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-tab>
            </x-tabs>

            <x-slot:actions>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <x-button label="{{ __('interface.back_button') }}" link="{{ route('users.index') }}" wire:navigate class="btn-secondary text-white w-full sm:w-auto" />
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
