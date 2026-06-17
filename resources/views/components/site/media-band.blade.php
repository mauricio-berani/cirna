@props([
    'image' => '',
    'eyebrow' => null,
    'title' => '',
    'text' => null,
])

<section class="relative h-64 sm:h-80 lg:h-96 overflow-hidden">
    <img src="{{ asset('assets/cirna/site/'.$image) }}" alt="{{ $title }}" loading="lazy"
        width="1280" height="800" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-r from-secondary/95 via-secondary/70 to-secondary/20"></div>
    <div class="absolute inset-0 blueprint opacity-30"></div>

    <div class="relative h-full flex items-center">
        <div class="mx-auto max-w-7xl w-full px-4 lg:px-8">
            <div class="max-w-xl text-secondary-content">
                @if ($eyebrow)
                    <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.2em] text-primary">
                        <span class="h-px w-6 bg-primary"></span>{{ $eyebrow }}
                    </span>
                @endif
                <h2 class="mt-3 text-2xl sm:text-3xl lg:text-4xl font-extrabold">{{ $title }}</h2>
                @if ($text)
                    <p class="mt-3 leading-relaxed text-secondary-content/80">{{ $text }}</p>
                @endif
            </div>
        </div>
    </div>
</section>
