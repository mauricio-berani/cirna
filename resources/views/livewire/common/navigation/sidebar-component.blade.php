<div
    x-data="{ collapsed: false }"
    @sidebar-toggled.window="collapsed = $event.detail"
    :class="{ 'sidebar-collapsed': collapsed }"
>
    {{-- Botão recolher no topo — desktop only --}}
    <div
        :class="collapsed ? 'justify-center px-0' : 'justify-end px-2'"
        class="hidden lg:flex pt-2 pb-1"
    >
        <button
            @click="$dispatch('toggle-sidebar')"
            class="p-2 rounded-lg hover:bg-base-200/80 text-base-content/50 hover:text-base-content transition-colors"
            :title="collapsed ? '{{ __('interface.expand') }}' : '{{ __('interface.collapse') }}'"
        >
            <span x-show="collapsed"><x-icon name="o-chevron-double-right" class="w-5 h-5" /></span>
            <span x-show="!collapsed"><x-icon name="o-chevron-double-left" class="w-5 h-5" /></span>
        </button>
    </div>

    {{-- Itens do menu --}}
    <x-menu activate-by-route class="menu menu-compact gap-0.5 px-0 py-1 text-base-content w-full">
        @foreach ($menus as $menu)
            @if (isset($menu['submenus']))
                <x-menu-sub title="{{ $menu['title'] }}"
                            icon="{{ $menu['icon'] }}"
                            :active="$menu['is_active'] ?? false"
                            class="hover:bg-base-200/80 active:bg-base-300 w-full rounded-none">
                    @foreach ($menu['submenus'] as $submenu)
                        <x-menu-item title="{{ $submenu['title'] }}"
                                     icon="o-chevron-right"
                                     link="{{ $submenu['link'] }}"
                                     wire:navigate
                                     route="{{ $submenu['route'] }}"
                                     exact
                                     :active="$submenu['is_active']"
                                     class="hover:bg-base-200/80 active:bg-base-300 w-full rounded-none" />
                    @endforeach
                </x-menu-sub>
            @else
                <x-menu-item title="{{ $menu['title'] }}"
                             icon="{{ $menu['icon'] }}"
                             link="{{ $menu['link'] }}"
                             wire:navigate
                             route="{{ $menu['route'] }}"
                             :active="$menu['is_active']"
                             exact
                             class="hover:bg-base-200/80 active:bg-base-300 w-full rounded-none" />
            @endif
        @endforeach
    </x-menu>
</div>
