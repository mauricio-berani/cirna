@props([
    'eyebrow' => null,
    'title' => '',
    'subtitle' => null,
    'align' => 'left', // left | center
])

<div @class([
    'max-w-2xl',
    'mx-auto text-center' => $align === 'center',
])>
    @if ($eyebrow)
        <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.2em] text-primary">
            <span class="h-px w-6 bg-primary"></span>{{ $eyebrow }}
        </span>
    @endif

    <h2 class="mt-3 text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-base-content">
        {{ $title }}
    </h2>

    @if ($subtitle)
        <p class="mt-4 text-base sm:text-lg leading-relaxed text-base-content/70">
            {{ $subtitle }}
        </p>
    @endif

    {{ $slot }}
</div>
