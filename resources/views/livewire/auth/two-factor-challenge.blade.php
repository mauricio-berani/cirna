<div class="flex flex-wrap h-screen">
    <div class="h-full w-1/2 p-4 shadow-md hidden md:block bg-login-background"></div>
    <div class="md:w-1/2 p-4 shadow-md h-screen w-screen flex items-center justify-center">
        <div class="p-4 sm:p-8 md:p-16 w-full max-w-md text-center">
            <h2 class="text-xl font-bold mb-4">{{ __('auth.two_factor_title') }}</h2>
            <p class="text-sm text-base-content/60 mb-6">{{ __('auth.two_factor_prompt') }}</p>

            <x-form wire:submit.prevent="verify">
                <div>
                    <x-input
                        label="{{ __('fields.two_factor_code') }}"
                        wire:model="code"
                        class="!outline-none text-center tracking-widest"
                        type="text"
                        maxlength="6"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        autofocus
                    />
                </div>

                <x-slot:actions>
                    <x-button label="{{ __('interface.cancel') }}" class="btn-ghost" wire:click="cancel" />
                    <x-button label="{{ __('interface.verify') }}" class="btn-primary text-white" type="submit" spinner="verify" />
                </x-slot:actions>
            </x-form>
        </div>
    </div>
</div>
