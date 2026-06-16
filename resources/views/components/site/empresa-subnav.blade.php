@php
    $tabs = [
        ['route' => 'site.empresa', 'label' => 'Institucional'],
        ['route' => 'site.historico', 'label' => 'Histórico'],
        ['route' => 'site.qualidade', 'label' => 'Qualidade'],
    ];
@endphp

<div class="flex flex-wrap gap-2">
    @foreach ($tabs as $tab)
        <a href="{{ route($tab['route']) }}" wire:navigate
            @class([
                'inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold transition',
                'bg-primary text-primary-content shadow-sm' => request()->routeIs($tab['route']),
                'bg-base-200 text-base-content/70 hover:bg-base-300 hover:text-base-content' => ! request()->routeIs($tab['route']),
            ])>
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>
