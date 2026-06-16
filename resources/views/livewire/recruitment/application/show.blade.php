<div>
    <livewire:common.table.header-component :title="$title" :breadcrumbs="$breadcrumbs" />

    <x-card class="shadow bg-base-300 mt-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Dados do candidato --}}
            <div class="lg:col-span-2">
                <h3 class="text-base font-semibold text-base-content mb-4">{{ __('interface.identification.applications.show.title') }}</h3>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-xl bg-base-100 border border-base-200/60 p-4">
                        <dt class="text-xs uppercase tracking-wide text-base-content/50">{{ __('fields.name') }}</dt>
                        <dd class="mt-1 font-medium text-base-content">{{ $item->name }}</dd>
                    </div>
                    <div class="rounded-xl bg-base-100 border border-base-200/60 p-4">
                        <dt class="text-xs uppercase tracking-wide text-base-content/50">{{ __('fields.area') }}</dt>
                        <dd class="mt-1 font-medium text-base-content">{{ $areaLabel }}</dd>
                    </div>
                    <div class="rounded-xl bg-base-100 border border-base-200/60 p-4">
                        <dt class="text-xs uppercase tracking-wide text-base-content/50">{{ __('fields.email') }}</dt>
                        <dd class="mt-1 font-medium text-base-content break-all">
                            <a href="mailto:{{ $item->email }}" class="text-primary hover:underline">{{ $item->email }}</a>
                        </dd>
                    </div>
                    <div class="rounded-xl bg-base-100 border border-base-200/60 p-4">
                        <dt class="text-xs uppercase tracking-wide text-base-content/50">{{ __('fields.phone') }}</dt>
                        <dd class="mt-1 font-medium text-base-content">{{ $item->phone ?: '—' }}</dd>
                    </div>
                    <div class="rounded-xl bg-base-100 border border-base-200/60 p-4">
                        <dt class="text-xs uppercase tracking-wide text-base-content/50">{{ __('fields.created_at') }}</dt>
                        <dd class="mt-1 font-medium text-base-content">{{ $item->created_at?->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Currículo --}}
            <div>
                <h3 class="text-base font-semibold text-base-content mb-4">{{ __('fields.resume') }}</h3>
                <div class="rounded-xl bg-base-100 border border-base-200/60 p-6 flex flex-col items-center text-center">
                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-primary/10 text-primary">
                        <x-icon name="o-document-text" class="w-7 h-7" />
                    </span>
                    <p class="mt-3 text-sm text-base-content/60">{{ __('site.careers.resume_pdf') }}</p>
                    <x-button label="{{ __('interface.download_button') }}" icon="o-arrow-down-tray"
                        wire:click="download" class="btn-primary text-white mt-4 w-full" spinner="download" />
                </div>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-base-200/60 flex sm:justify-start">
            <x-button label="{{ __('interface.back_button') }}" link="{{ route('candidaturas.index') }}" wire:navigate
                class="btn-secondary text-white w-full sm:w-auto" />
        </div>
    </x-card>
</div>
