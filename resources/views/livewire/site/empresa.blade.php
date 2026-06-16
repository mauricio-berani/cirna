<div>
    <x-site.page-hero
        eyebrow="Empresa"
        title="Institucional"
        subtitle="Conheça quem é a Cirna, no que acreditamos e o que nos move há mais de cinco décadas."
        :breadcrumbs="[['label' => 'Empresa']]">
        <div class="mt-8">
            <x-site.empresa-subnav />
        </div>
    </x-site.page-hero>

    <section class="bg-base-100">
        <div class="mx-auto max-w-7xl px-4 lg:px-8 py-16 sm:py-20">
            <div class="grid gap-6 lg:grid-cols-2">
                {{-- Missão --}}
                <div class="rounded-2xl border border-base-300/70 bg-base-100 p-6 sm:p-8 lg:row-span-2 flex flex-col">
                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-primary/10 text-primary">
                        <x-icon name="o-flag" class="w-7 h-7" />
                    </span>
                    <h2 class="mt-5 text-2xl font-extrabold text-base-content">Missão</h2>
                    <p class="mt-4 text-lg leading-relaxed text-base-content/75">
                        Produzir peças com qualidade e que garantam a confiança e a total satisfação dos clientes.
                    </p>
                    <div class="mt-auto pt-8">
                        <div class="rounded-xl bg-base-200 p-5">
                            <p class="text-sm text-base-content/70">
                                <span class="font-semibold text-base-content">Negócio:</span>
                                produção de peças plásticas injetadas.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Princípios --}}
                <div class="rounded-2xl border border-base-300/70 bg-base-100 p-6 sm:p-8">
                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-primary/10 text-primary">
                        <x-icon name="o-shield-check" class="w-7 h-7" />
                    </span>
                    <h2 class="mt-5 text-2xl font-extrabold text-base-content">Princípios</h2>
                    <ul class="mt-5 space-y-3">
                        @foreach ([
                            'Respeito aos clientes, colaboradores e fornecedores.',
                            'Comprometimento com a qualidade.',
                            'Determinação na busca por resultados positivos.',
                            'Preservação do nome da empresa.',
                        ] as $principio)
                            <li class="flex items-start gap-3">
                                <x-icon name="o-check-circle" class="w-6 h-6 text-primary shrink-0" />
                                <span class="text-base-content/80">{{ $principio }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Atalhos --}}
                <div class="grid gap-4 sm:grid-cols-2">
                    <a href="{{ route('site.historico') }}" wire:navigate
                        class="group rounded-2xl bg-secondary text-secondary-content p-6 flex flex-col justify-between transition hover:opacity-95">
                        <x-icon name="o-clock" class="w-8 h-8 text-primary" />
                        <div class="mt-6">
                            <h3 class="text-lg font-bold">Histórico</h3>
                            <span class="mt-1 inline-flex items-center gap-1 text-sm text-secondary-content/70 group-hover:text-primary transition">
                                Nossa trajetória <x-icon name="o-arrow-right" class="w-4 h-4" />
                            </span>
                        </div>
                    </a>
                    <a href="{{ route('site.qualidade') }}" wire:navigate
                        class="group rounded-2xl bg-primary text-primary-content p-6 flex flex-col justify-between transition hover:opacity-95">
                        <x-icon name="o-check-badge" class="w-8 h-8" />
                        <div class="mt-6">
                            <h3 class="text-lg font-bold">Qualidade</h3>
                            <span class="mt-1 inline-flex items-center gap-1 text-sm text-primary-content/80 group-hover:text-primary-content transition">
                                {{ config('client.certification') }} <x-icon name="o-arrow-right" class="w-4 h-4" />
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <x-site.media-band
        image="fabrica.jpg"
        eyebrow="Parque fabril"
        title="Estrutura para produzir com qualidade"
        text="Ferramentaria e injeção integradas, com processos controlados para atender diferentes segmentos da indústria." />
</div>
