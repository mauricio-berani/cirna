<div>
    <x-site.page-hero
        eyebrow="Clientes"
        title="Empresas que confiam na Cirna"
        subtitle="Ao longo de mais de cinco décadas, construímos parcerias sólidas fornecendo componentes plásticos para indústrias de referência."
        :breadcrumbs="[['label' => 'Clientes']]" />

    <section class="bg-base-100">
        <div class="mx-auto max-w-7xl px-4 lg:px-8 py-16 sm:py-20">
            <p class="text-center text-base-content/70">Confira alguns de nossos clientes:</p>

            <div class="mt-10 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($clients as $client)
                    <a @if ($client->url) href="{{ $client->url }}" target="_blank" rel="noopener" @endif
                        class="group flex items-center justify-center rounded-2xl bg-base-100 border border-base-300/60 p-6 sm:p-8 h-32 transition hover:border-primary/40 hover:shadow-md"
                        aria-label="{{ $client->name }}">
                        <img src="{{ $client->logoUrl() }}" alt="{{ $client->name }}"
                            loading="lazy" class="client-logo max-h-14 w-auto object-contain">
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-base-200">
        <div class="mx-auto max-w-5xl px-4 lg:px-8 py-16 sm:py-20 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-base-content">
                Quer ser o próximo a contar com a Cirna?
            </h2>
            <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-base-content/70">
                Junte-se às empresas que confiam na nossa qualidade em moldes e injeção de plásticos.
            </p>
            <a href="{{ route('site.contato') }}" wire:navigate
                class="btn btn-primary text-primary-content mt-8 w-full sm:w-auto gap-2 rounded-full px-6">
                Fale com a Cirna <x-icon name="o-arrow-right" class="w-5 h-5" />
            </a>
        </div>
    </section>
</div>
