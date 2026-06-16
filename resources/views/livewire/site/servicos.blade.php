<div>
    <x-site.page-hero
        eyebrow="Serviços"
        title="Do desenvolvimento à peça injetada"
        subtitle="A Cirna é especializada no desenvolvimento de novos produtos, fabricação de moldes e injeção de peças plásticas — tudo com um único parceiro."
        :breadcrumbs="[['label' => 'Serviços']]" />

    {{-- Serviços principais --}}
    <section class="bg-base-100">
        <div class="mx-auto max-w-7xl px-4 lg:px-8 py-16 sm:py-20 space-y-6">
            @foreach ([
                [
                    'icon' => 'o-light-bulb',
                    'image' => 'desenvolvimento.jpg',
                    'title' => 'Desenvolvimento de novos produtos',
                    'desc' => 'Transformamos ideias em produtos viáveis, considerando função, custo e manufaturabilidade desde o início do projeto.',
                    'features' => ['Análise de viabilidade', 'Apoio ao projeto da peça', 'Prototipagem', 'Otimização para injeção'],
                ],
                [
                    'icon' => 'o-wrench-screwdriver',
                    'image' => 'molde.jpg',
                    'title' => 'Fabricação de moldes',
                    'desc' => 'Nossa origem desde 1972. Projetamos e fabricamos moldes de injeção sob medida, com precisão, durabilidade e manutenção.',
                    'features' => ['Projeto de moldes', 'Ferramentaria própria', 'Moldes sob medida', 'Manutenção e ajustes'],
                ],
                [
                    'icon' => 'o-beaker',
                    'image' => 'injecao.jpg',
                    'title' => 'Injeção de plásticos',
                    'desc' => 'Produzimos peças plásticas injetadas em escala, utilizadas como componentes para os mais diversos segmentos de mercado.',
                    'features' => ['Produção seriada', 'Controle de qualidade', 'Diversos polímeros', 'Componentes técnicos'],
                ],
            ] as $i => $servico)
                <div class="grid gap-6 lg:gap-10 overflow-hidden rounded-3xl border border-base-300/70 bg-base-100 p-4 sm:p-6 lg:grid-cols-[20rem_1fr] lg:items-center">
                    <div class="relative h-52 lg:h-72 overflow-hidden rounded-2xl">
                        <img src="{{ asset('assets/cirna/site/'.$servico['image']) }}" alt="{{ $servico['title'] }}"
                            loading="lazy" width="1280" height="800" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary text-primary-content shadow-lg">
                            <x-icon name="{{ $servico['icon'] }}" class="w-6 h-6" />
                        </span>
                    </div>
                    <div class="lg:px-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-primary">0{{ $i + 1 }}</span>
                        <h2 class="mt-1 text-2xl font-extrabold text-base-content">{{ $servico['title'] }}</h2>
                        <p class="mt-3 text-base-content/70 leading-relaxed">{{ $servico['desc'] }}</p>
                        <ul class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($servico['features'] as $feature)
                                <li class="flex items-center gap-2 text-sm text-base-content/80">
                                    <x-icon name="o-check-circle" class="w-5 h-5 text-primary shrink-0" />
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Segmentos --}}
    <section class="bg-base-200">
        <div class="mx-auto max-w-7xl px-4 lg:px-8 py-16 sm:py-20">
            <x-site.section-heading align="center"
                eyebrow="Segmentos"
                title="Componentes para diversos mercados"
                subtitle="Nossas peças plásticas equipam produtos de setores que exigem precisão e confiabilidade." />

            <div class="mt-12 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach ([
                    ['icon' => 'o-truck', 'label' => 'Automotivo'],
                    ['icon' => 'o-bolt', 'label' => 'Rodoviário'],
                    ['icon' => 'o-cog-6-tooth', 'label' => 'Autopeças'],
                    ['icon' => 'o-home-modern', 'label' => 'Eletrodomésticos'],
                    ['icon' => 'o-cube', 'label' => 'Industrial'],
                ] as $seg)
                    <div class="flex flex-col items-center text-center rounded-2xl bg-base-100 border border-base-300/60 p-6">
                        <x-icon name="{{ $seg['icon'] }}" class="w-8 h-8 text-primary" />
                        <span class="mt-3 font-semibold text-base-content text-sm">{{ $seg['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-base-100">
        <div class="mx-auto max-w-5xl px-4 lg:px-8 py-16 sm:py-20 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-base-content">
                Precisa de um molde ou de peças injetadas?
            </h2>
            <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-base-content/70">
                Envie os detalhes do seu projeto e receba um atendimento personalizado da nossa equipe técnica.
            </p>
            <a href="{{ route('site.contato') }}" wire:navigate
                class="btn btn-primary text-primary-content mt-8 w-full sm:w-auto gap-2 rounded-full px-6">
                Solicitar orçamento <x-icon name="o-arrow-right" class="w-5 h-5" />
            </a>
        </div>
    </section>
</div>
