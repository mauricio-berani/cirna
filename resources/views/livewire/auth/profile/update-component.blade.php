<div class="mary-card mary-shadow-lg mary-rounded-md mary-p-4">
    <livewire:common.table.header-component :title="$title" :subtitle="$subtitle" :breadcrumbs="$breadcrumbs" />

    <div class="bg-base-300 p-4 sm:p-8 lg:p-12">
        <x-form wire:submit.prevent="update">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-4 flex justify-center items-center">
                    <x-file wire:model="form.avatar" accept="image/jpeg,image/png,image/gif,image/webp" crop-after-change>
                        <img src="{{ $avatarPath ?? $defaultAvatar }}" class="rounded-full h-60 w-60 shadow" />
                    </x-file>
                </div>

                <div class="col-span-12 md:col-span-8 grid grid-cols-12 gap-4">
                    <div class="col-span-12">
                        <x-input label="Nome" wire:model="form.name" error-class="text-error m-1 p-1" />
                    </div>

                    <div class="col-span-12 lg:col-span-4">
                        <x-password label="Senha atual" wire:model="form.current_password" error-class="text-error m-1 p-1" right />
                    </div>

                    <div class="col-span-12 lg:col-span-4">
                        <x-password label="Senha" wire:model="form.password" error-class="text-error m-1 p-1" right />
                    </div>

                    <div class="col-span-12 lg:col-span-4">
                        <x-password label="Confirmação de senha" wire:model="form.password_confirmation" error-class="text-error m-1 p-1" right />
                    </div>
                </div>
            </div>

            <x-slot:actions>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <x-button label="Voltar" link="{{ route('dashboard') }}" wire:navigate class="btn-secondary text-white w-full sm:w-auto"/>
                    <x-button label="Atualizar" class="btn-primary text-white w-full sm:w-auto" type="submit" spinner="update" />
                </div>
            </x-slot:actions>
        </x-form>
    </div>
</div>
