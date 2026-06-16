<div>
    <x-card title="{{ __('auth.two_factor_section_title') }}" subtitle="{{ __('auth.two_factor_section_subtitle') }}">
        @if($enabled)
            <div class="alert alert-success mb-4">
                <span>{{ __('auth.two_factor_active') }}</span>
            </div>
            <x-form wire:submit.prevent="disable" class="max-w-xs">
                <x-input
                    label="{{ __('fields.two_factor_code') }}"
                    wire:model="disableCode"
                    class="!outline-none text-center tracking-widest"
                    type="text"
                    maxlength="6"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                />

                <x-slot:actions>
                    <x-button
                        label="{{ __('auth.two_factor_disable') }}"
                        class="btn-error text-white"
                        type="submit"
                        spinner="disable"
                    />
                </x-slot:actions>
            </x-form>
        @elseif($qrCodeSvg)
            <div class="flex flex-col items-center gap-4">
                <p class="text-sm text-base-content/60">{{ __('auth.two_factor_scan_qr') }}</p>
                {{-- bg-white é obrigatório aqui: o QR precisa de fundo claro para ser escaneável no dark theme --}}
                <div class="p-4 bg-white rounded-lg shadow">
                    {!! $qrCodeSvg !!}
                </div>
                <p class="text-xs text-base-content/40 font-mono break-all max-w-xs">{{ $secret }}</p>

                <x-form wire:submit.prevent="enable" class="w-full max-w-xs">
                    <x-input
                        label="{{ __('fields.two_factor_code') }}"
                        wire:model="code"
                        class="!outline-none text-center tracking-widest"
                        type="text"
                        maxlength="6"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                    />
                    <x-slot:actions>
                        <x-button label="{{ __('auth.two_factor_confirm') }}" class="btn-primary text-white" type="submit" spinner="enable" />
                    </x-slot:actions>
                </x-form>
            </div>
        @else
            <p class="text-sm text-base-content/60 mb-4">{{ __('auth.two_factor_not_active') }}</p>
            <x-button
                label="{{ __('auth.two_factor_enable') }}"
                class="btn-primary text-white"
                wire:click="generateSecret"
                spinner="generateSecret"
            />
        @endif
    </x-card>
</div>
