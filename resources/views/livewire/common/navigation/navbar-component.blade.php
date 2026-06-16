<x-nav full-width class="bg-base-100 border-b border-base-200 shadow-sm px-4">
    <x-slot:brand>
        <label for="main-drawer" class="lg:hidden mr-3 flex items-center">
            <x-icon name="o-bars-3" class="cursor-pointer icon-md text-base-content/80 hover:text-primary transition" />
        </label>

        <img
            src="{{ asset('assets/images/logotipo.png') }}"
            srcset="{{ asset('assets/images/logotipo-160.png') }} 160w, {{ asset('assets/images/logotipo-320.png') }} 320w, {{ asset('assets/images/logotipo-512.png') }} 512w"
            sizes="(min-width: 768px) 80px, 40px"
            width="320"
            height="70"
            alt="Blib Tech Logo"
            class="w-10 md:w-20"
        />
    </x-slot:brand>

    <x-slot:actions>
        <div class="grid gap-4 w-full cursor-pointer">
            <div class="col-start-2 md:col-start-4 justify-self-end flex items-center gap-2">
                <x-dropdown>
                    <x-slot:trigger>
                        <div class="flex items-center gap-3 px-3 py-1.5 rounded-full bg-base-200/60 border border-base-100/30 hover:border-primary/60 transition">
                            <x-icon name="c-ellipsis-vertical" class="icon-md text-base-content/80" />
                            <x-avatar :image="$avatarPath" class="shadow-sm">
                                <x-slot:title class="!font-bold pl-2">
                                    {{ $user->name }}
                                </x-slot:title>
                            </x-avatar>
                        </div>
                    </x-slot:trigger>

                    <x-menu-item title="{{ __('interface.identification.profile.title') }}" icon="o-user-circle" link="{{ route('profile') }}" wire:navigate />
                    <x-menu-item title="{{ __('interface.logout_button') }}" icon="o-power" wire:click="logout" />
                </x-dropdown>
            </div>
        </div>
    </x-slot:actions>
</x-nav>
