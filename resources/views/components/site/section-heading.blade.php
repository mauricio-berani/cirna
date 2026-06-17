@props([
    'eyebrow' => null,
    'title' => '',
    'subtitle' => null,
    'align' => 'left', // left | center
    'index' => null,   // ex.: "01"
])

<div @class([
    'max-w-2xl',
    'mx-auto text-center' => $align === 'center',
])>
    @if ($eyebrow)
        <span @class(['site-kicker', 'justify-center' => $align === 'center'])>
            @if ($index)<span class="site-index">{{ $index }}</span>@endif{{ $eyebrow }}
        </span>
    @endif

    <h2 class="mt-4 text-3xl sm:text-4xl lg:text-[2.6rem] font-extrabold leading-[1.05] text-base-content">
        {{ $title }}
    </h2>

    @if ($subtitle)
        <p class="mt-4 text-base sm:text-lg leading-relaxed text-base-content/65">
            {{ $subtitle }}
        </p>
    @endif

    {{ $slot }}
</div>
