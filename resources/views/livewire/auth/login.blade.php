<div class="flex flex-wrap h-screen">
    <div class="h-full w-1/2 p-4 shadow-md hidden md:block bg-login-background"></div>
    <div class="md:w-1/2 p-4 shadow-md h-screen w-screen flex items-center justify-center">
        <div class="p-4 sm:p-8 md:p-16 w-full max-w-md">
            <figure class="w-full my-4">
                <img
                    src="{{ asset('assets/images/logotipo.png') }}"
                    srcset="{{ asset('assets/images/logotipo-160.png') }} 160w, {{ asset('assets/images/logotipo-320.png') }} 320w, {{ asset('assets/images/logotipo-512.png') }} 512w"
                    sizes="128px"
                    width="320"
                    height="70"
                    alt="Blib"
                    class="items-center w-32 m-auto"
                >
            </figure>
            <x-form wire:submit.prevent="login">
                <div>
                    <x-input label="{{ __('fields.email') }}" wire:model="form.email" class="!outline-none" type="email" />
                </div>

                <div>
                    <x-password label="{{ __('fields.password') }}" wire:model="form.password" class="!outline-none" right />
                </div>

                <x-slot:actions>
                    <x-button label="{{ __('interface.login_button') }}" class="btn-primary w-full text-white" type="submit" spinner="save" />
                </x-slot:actions>
            </x-form>
        </div>
    </div>
</div>
