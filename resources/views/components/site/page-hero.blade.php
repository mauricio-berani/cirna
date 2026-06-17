@props([
    'eyebrow' => null,
    'title' => '',
    'subtitle' => null,
    'breadcrumbs' => [], // [['label' => '...', 'route' => '...'], ['label' => 'Atual']]
])

<section class="site-hero border-b border-base-300/70">
    <div class="relative mx-auto max-w-7xl px-4 lg:px-8 py-14 sm:py-20 lg:py-24 site-reveal">
        @if (! empty($breadcrumbs))
            <nav aria-label="breadcrumb" class="mb-6 flex flex-wrap items-center gap-2 text-sm text-base-content/55">
                <a href="{{ route('site.home') }}" wire:navigate class="hover:text-primary transition">Home</a>
                @foreach ($breadcrumbs as $crumb)
                    <x-icon name="o-chevron-right" class="w-4 h-4" />
                    @if (! empty($crumb['route']))
                        <a href="{{ route($crumb['route']) }}" wire:navigate class="hover:text-primary transition">{{ $crumb['label'] }}</a>
                    @else
                        <span class="text-base-content font-medium">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @endif

        @if ($eyebrow)
            <span class="site-kicker">{{ $eyebrow }}</span>
        @endif

        <h1 class="mt-4 max-w-3xl text-4xl sm:text-5xl lg:text-[3.5rem] font-extrabold leading-[1.02] text-base-content">
            {{ $title }}
        </h1>

        @if ($subtitle)
            <p class="mt-5 max-w-2xl text-base sm:text-lg leading-relaxed text-base-content/65">
                {{ $subtitle }}
            </p>
        @endif

        {{ $slot }}
    </div>
</section>
