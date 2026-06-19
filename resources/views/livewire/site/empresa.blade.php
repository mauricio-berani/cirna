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
        image="cirna-parque-injetoras.jpg"
        eyebrow="Parque fabril"
        title="Estrutura para produzir do molde à peça"
        text="Ferramentaria e injeção integradas sob o mesmo teto, com processos controlados para atender diversos segmentos da indústria." />

    {{-- ===== GALERIA PARQUE FABRIL ===== --}}
    <section id="parque-fabril" class="bg-base-200 border-t border-base-300/70">
        <div class="mx-auto max-w-7xl px-4 lg:px-8 py-16 sm:py-20">
            <x-site.section-heading
                eyebrow="Nossos setores"
                title="Conheça por dentro a fábrica"
                subtitle="Imagens reais da nossa matrizaria e do setor de injeção de plásticos." />

            @php
                $sectors = [
                    [
                        'name' => 'Matrizaria',
                        'icon' => 'o-wrench-screwdriver',
                        'desc' => 'Projeto e fabricação de moldes',
                        'photos' => [
                            ['img' => 'cirna-usinagem-cnc-moldes.jpg', 'caption' => 'Centro de usinagem CNC'],
                            ['img' => 'cirna-matrizaria-centros-usinagem.jpg', 'caption' => 'Usinagem de precisão'],
                            ['img' => 'cirna-ferramentaria-fabricacao-moldes.jpg', 'caption' => 'Ferramentaria própria'],
                        ],
                    ],
                    [
                        'name' => 'Injeção de Plásticos',
                        'icon' => 'o-beaker',
                        'desc' => 'Produção de peças plásticas injetadas',
                        'photos' => [
                            ['img' => 'cirna-injetora-plasticos.jpg', 'caption' => 'Injeção de peças plásticas'],
                            ['img' => 'cirna-painel-controle-injetora.jpg', 'caption' => 'Controle de processo'],
                            ['img' => 'cirna-linha-injecao-plasticos.jpg', 'caption' => 'Linha de produção'],
                        ],
                    ],
                ];
            @endphp

            @foreach ($sectors as $sector)
                <div class="mt-12 first:mt-12">
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-primary/10 text-primary shrink-0">
                            <x-icon name="{{ $sector['icon'] }}" class="w-6 h-6" />
                        </span>
                        <div>
                            <h3 class="font-display text-xl font-bold text-base-content">{{ $sector['name'] }}</h3>
                            <p class="text-sm text-base-content/55">{{ $sector['desc'] }}</p>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($sector['photos'] as $photo)
                            <figure class="group relative overflow-hidden rounded-2xl border border-base-300/60">
                                <img src="{{ asset('assets/cirna/site/'.$photo['img']) }}"
                                    alt="{{ $photo['caption'] }} — {{ $sector['name'] }} | Cirna"
                                    loading="lazy" width="1504" height="992"
                                    class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-secondary/85 via-secondary/10 to-transparent"></div>
                                <figcaption class="absolute inset-x-0 bottom-0 p-4 flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 rounded-full bg-primary shrink-0"></span>
                                    <span class="text-sm font-semibold text-secondary-content">{{ $photo['caption'] }}</span>
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
