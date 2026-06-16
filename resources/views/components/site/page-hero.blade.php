@props([
    'eyebrow' => null,
    'title' => '',
    'subtitle' => null,
    'breadcrumbs' => [], // [['label' => '...', 'route' => '...'], ['label' => 'Atual']]
])

<section class="site-hero border-b border-base-300/70">
    <div class="mx-auto max-w-7xl px-4 lg:px-8 py-12 sm:py-16 lg:py-20 site-reveal">
        @if (! empty($breadcrumbs))
            <nav aria-label="breadcrumb" class="mb-5 flex flex-wrap items-center gap-2 text-sm text-base-content/60">
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
            <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.2em] text-primary">
                <span class="h-px w-6 bg-primary"></span>{{ $eyebrow }}
            </span>
        @endif

        <h1 class="mt-3 max-w-3xl text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-base-content">
            {{ $title }}
        </h1>

        @if ($subtitle)
            <p class="mt-4 max-w-2xl text-base sm:text-lg leading-relaxed text-base-content/70">
                {{ $subtitle }}
            </p>
        @endif

        {{ $slot }}
    </div>
</section>
