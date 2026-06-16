<div>
    <livewire:common.table.header-component :title="$title" :subtitle="$subtitle" :breadcrumbs="$breadcrumbs" />

    <x-card class="shadow bg-base-300 mt-6">
        <x-form wire:submit.prevent="update" class="max-w-2xl">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-8">
                    <x-input
                        label="{{ __('fields.careers_email') }}"
                        wire:model="form.careers_email"
                        type="email"
                        placeholder="{{ $fallbackEmail }}"
                        hint="{{ __('interface.identification.settings.careers_email_hint') }}"
                        error-class="text-error m-1 p-1"
                    />
                </div>

                <div class="col-span-12">
                    <hr class="my-2">
                    <h3 class="text-base font-semibold text-base-content">{{ __('fields.iso_certificate') }}</h3>
                    <p class="text-sm text-base-content/60 mb-3">{{ __('interface.identification.settings.iso_certificate_hint') }}</p>

                    @if ($certificateUrl)
                        <a href="{{ $certificateUrl }}" target="_blank" rel="noopener"
                            class="inline-flex items-center gap-2 mb-3 rounded-lg bg-base-100 border border-base-200/60 px-4 py-2 text-sm text-primary hover:underline">
                            <x-icon name="o-document-text" class="w-5 h-5" />
                            {{ __('interface.identification.settings.iso_certificate_current') }}
                        </a>
                    @endif

                    <x-file
                        label="{{ __('interface.identification.settings.iso_certificate_upload') }}"
                        wire:model="form.certificate"
                        accept="application/pdf"
                        hint="{{ __('site.careers.resume_hint') }}"
                        error-class="text-error m-1 p-1"
                    />

                    <div wire:loading wire:target="form.certificate" class="mt-2 text-sm text-base-content/60">
                        <x-icon name="o-arrow-path" class="w-4 h-4 inline animate-spin" />
                        {{ __('site.careers.uploading') }}
                    </div>
                </div>
            </div>

            <x-slot:actions>
                <x-button label="{{ __('interface.update_button') }}" type="submit"
                    class="btn-primary text-white w-full sm:w-auto" spinner="update" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
