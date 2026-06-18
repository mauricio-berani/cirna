@php
    $links = array_values(array_filter([
        ['route' => 'site.home', 'label' => 'Home'],
        ['route' => 'site.empresa', 'label' => 'Empresa', 'group' => ['site.empresa', 'site.historico', 'site.qualidade']],
        ['route' => 'site.servicos', 'label' => 'Serviços'],
        \App\Models\Common\Setting::showClientsSection() ? ['route' => 'site.clientes', 'label' => 'Clientes'] : null,
        ['route' => 'site.contato', 'label' => 'Contato'],
    ]));

    $isActive = fn (array $link) => request()->routeIs($link['group'] ?? $link['route']);
@endphp

<header
    x-data="{ open: false, scrolled: false }"
    x-init="scrolled = window.scrollY > 8; window.addEventListener('scroll', () => scrolled = window.scrollY > 8)"
    class="sticky top-0 z-50"
>
    {{-- Faixa superior de contato (oculta no mobile) --}}
    <div class="hidden md:block bg-secondary text-secondary-content/90">
        <div class="mx-auto max-w-7xl px-4 lg:px-8 flex items-center justify-between h-9 text-xs">
            <span class="inline-flex items-center gap-2">
                <x-icon name="o-map-pin" class="w-4 h-4 text-primary" />
                {{ config('client.city') }}/{{ config('client.state') }}
            </span>
            <div class="flex items-center gap-5">
                <a href="tel:{{ config('client.phone_e164') }}" class="inline-flex items-center gap-2 hover:text-primary transition">
                    <x-icon name="o-phone" class="w-4 h-4" /> {{ config('client.phone') }}
                </a>
                <a href="mailto:{{ config('client.email') }}" class="inline-flex items-center gap-2 hover:text-primary transition">
                    <x-icon name="o-envelope" class="w-4 h-4" /> {{ config('client.email') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Barra principal --}}
    <nav
        class="border-b border-base-300/70 transition-all duration-300"
        :class="scrolled ? 'bg-base-100/95 backdrop-blur shadow-sm' : 'bg-base-100'"
    >
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <a href="{{ route('site.home') }}" wire:navigate class="flex items-center shrink-0" aria-label="Cirna — página inicial">
                    <img src="{{ asset('assets/cirna/cirna-logo.png') }}" alt="Cirna Indústria de Plásticos e Moldes"
                        width="225" height="44" class="h-8 lg:h-10 w-auto">
                </a>

                {{-- Menu desktop --}}
                <div class="hidden lg:flex items-center gap-8">
                    @foreach ($links as $link)
                        <a href="{{ route($link['route']) }}" wire:navigate
                            @class(['public-nav-link', 'public-nav-link-active' => $isActive($link)])
                            @if ($isActive($link)) aria-current="page" @endif>
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="hidden lg:block">
                    <a href="{{ route('site.contato') }}" wire:navigate
                        class="btn btn-primary btn-sm rounded-full px-5 text-primary-content gap-2">
                        <x-icon name="o-paper-airplane" class="w-4 h-4" /> Solicitar orçamento
                    </a>
                </div>

                {{-- Botão mobile --}}
                <button type="button" @click="open = !open"
                    class="lg:hidden inline-flex items-center justify-center w-11 h-11 rounded-lg text-base-content hover:bg-base-200 transition"
                    :aria-expanded="open.toString()" aria-label="Abrir menu">
                    <x-icon name="o-bars-3" class="w-6 h-6" x-show="!open" />
                    <x-icon name="o-x-mark" class="w-6 h-6" x-show="open" x-cloak />
                </button>
            </div>
        </div>

        {{-- Menu mobile --}}
        <div x-show="open" x-collapse x-cloak class="lg:hidden border-t border-base-300/70 bg-base-100">
            <div class="px-4 py-4 space-y-1">
                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}" wire:navigate @click="open = false"
                        @class([
                            'block py-3 px-3 rounded-lg public-nav-link-mobile',
                            'bg-primary/10 text-primary' => $isActive($link),
                        ])>
                        {{ $link['label'] }}
                    </a>
                @endforeach
                <a href="{{ route('site.contato') }}" wire:navigate @click="open = false"
                    class="btn btn-primary w-full mt-3 text-primary-content gap-2">
                    <x-icon name="o-paper-airplane" class="w-4 h-4" /> Solicitar orçamento
                </a>
            </div>
        </div>
    </nav>
</header>
