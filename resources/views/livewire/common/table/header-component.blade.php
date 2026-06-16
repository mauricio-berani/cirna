<x-header :title="$title" size="text-xl" separator progress-indicator>
    <x-slot:actions>
        <div class="breadcrumbs text-sm">
            <ul>
                <li><a href="{{ route('dashboard') }}" wire:navigate><x-icon name="o-home" /></a></li>
                @foreach ($breadcrumbs as $breadcrumb)
                @if (!isset($breadcrumb['link']))
                <li>{{ $breadcrumb['title'] }}</li>
                @else
                <li><a href="{{ $breadcrumb['link'] }}" wire:navigate>{{ $breadcrumb['title'] }}</a></li>
                @endif
                @endforeach
            </ul>
        </div>
    </x-slot:actions>
</x-header>
