<div>
    {{-- ===== HERO ===== --}}
    <section class="site-hero">
        <div class="mx-auto max-w-7xl px-4 lg:px-8 py-16 sm:py-20 lg:py-28">
            <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
                <div class="site-reveal">
                    <span class="site-kicker">Desde {{ config('client.founded_year') }} · {{ config('client.city') }}/{{ config('client.state') }}</span>

                    <h1 class="mt-5 text-[2.75rem] sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-base-content leading-[0.98]">
                        Moldes e injeção de
                        <span class="text-primary">plásticos</span>
                        com precisão
                    </h1>

                    <p class="mt-6 max-w-xl text-base sm:text-lg leading-relaxed text-base-content/65">
                        Desenvolvemos produtos, fabricamos moldes e injetamos peças plásticas para os mais diversos
                        segmentos da indústria — com qualidade certificada e compromisso com prazos.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('site.contato') }}" wire:navigate
                            class="btn btn-primary text-primary-content w-full sm:w-auto gap-2 rounded-full px-6">
                            Solicitar orçamento <x-icon name="o-arrow-right" class="w-5 h-5" />
                        </a>
                        <a href="{{ route('site.servicos') }}" wire:navigate
                            class="btn btn-outline border-base-300 text-base-content hover:border-primary hover:text-primary w-full sm:w-auto rounded-full px-6">
                            Nossos serviços
                        </a>
                    </div>

                    {{-- Estatísticas com divisórias hairline --}}
                    <dl class="mt-12 grid grid-cols-3 max-w-lg divide-x divide-base-300/70">
                        <div class="pr-4">
                            <dt class="font-display text-4xl sm:text-5xl font-extrabold text-base-content tabular-nums">+{{ $yearsInMarket }}</dt>
                            <dd class="mt-1 text-xs sm:text-sm text-base-content/55">anos de mercado</dd>
                        </div>
                        <div class="px-4">
                            <dt class="font-display text-4xl sm:text-5xl font-extrabold text-base-content tabular-nums">8+</dt>
                            <dd class="mt-1 text-xs sm:text-sm text-base-content/55">segmentos atendidos</dd>
                        </div>
                        <div class="pl-4">
                            <dt class="font-display text-4xl sm:text-5xl font-extrabold text-base-content tabular-nums">100%</dt>
                            <dd class="mt-1 text-xs sm:text-sm text-base-content/55">foco em qualidade</dd>
                        </div>
                    </dl>
                </div>

                {{-- Painel visual com imagens reais --}}
                <div class="site-reveal lg:justify-self-end w-full max-w-lg">
                    <div class="relative">
                        <div class="overflow-hidden rounded-3xl shadow-xl ring-1 ring-base-300/60">
                            <img src="{{ asset('assets/cirna/site/producao.jpg') }}"
                                alt="Linha de produção industrial da Cirna"
                                width="1280" height="800"
                                class="w-full h-80 sm:h-[26rem] object-cover">
                        </div>

                        {{-- Imagem sobreposta: molde de injeção --}}
                        <div class="absolute -bottom-8 -left-4 sm:-left-10 w-40 sm:w-56 overflow-hidden rounded-2xl shadow-lg ring-4 ring-base-100 hidden sm:block">
                            <img src="{{ asset('assets/cirna/site/molde.jpg') }}"
                                alt="Molde de injeção fabricado pela Cirna"
                                width="640" height="400"
                                class="w-full h-36 sm:h-44 object-cover">
                        </div>

                        {{-- Selo de certificação flutuante --}}
                        <div class="absolute -top-4 -right-3 sm:-right-5 flex items-center gap-2 rounded-2xl bg-base-100 px-4 py-3 shadow-lg ring-1 ring-base-300/60">
                            <x-icon name="o-check-badge" class="w-6 h-6 text-primary" />
                            <div class="leading-tight">
                                <p class="text-[0.65rem] uppercase tracking-wide text-base-content/55">Certificação</p>
                                <p class="text-sm font-bold text-base-content">{{ config('client.certification') }}</p>
                            </div>
                        </div>

                        {{-- Selo EST. 1972 --}}
                        <div class="site-seal absolute -bottom-6 right-6 w-20 h-20 bg-base-100 shadow-lg">
                            <span class="text-[0.55rem] font-bold uppercase tracking-[0.2em] text-base-content/55">Est.</span>
                            <span class="font-display text-2xl font-extrabold text-primary tabular-nums">1972</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== O QUE FAZEMOS ===== --}}
    <section class="bg-base-100">
        <div class="mx-auto max-w-7xl px-4 lg:px-8 py-16 sm:py-20 lg:py-24">
            <x-site.section-heading
                index="01"
                eyebrow="O que fazemos"
                title="Uma solução completa em plásticos"
                subtitle="Do conceito à peça final, integramos desenvolvimento, ferramentaria e injeção em um só fornecedor." />

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['icon' => 'o-light-bulb', 'image' => 'desenvolvimento.jpg', 'title' => 'Desenvolvimento de produtos', 'desc' => 'Apoiamos a criação de novos produtos, da ideia ao protótipo, pensando em manufaturabilidade e custo.'],
                    ['icon' => 'o-wrench-screwdriver', 'image' => 'molde.jpg', 'title' => 'Fabricação de moldes', 'desc' => 'Nossa origem. Projetamos e fabricamos moldes de injeção sob medida, com precisão e durabilidade.'],
                    ['icon' => 'o-beaker', 'image' => 'injecao.jpg', 'title' => 'Injeção de plásticos', 'desc' => 'Produzimos peças plásticas injetadas como componentes para diversos segmentos de mercado.'],
                ] as $item)
                    <div class="group overflow-hidden rounded-2xl border border-base-300/70 bg-base-100 transition hover:border-primary/40 hover:shadow-lg">
                        <div class="relative h-44 overflow-hidden">
                            <img src="{{ asset('assets/cirna/site/'.$item['image']) }}" alt="{{ $item['title'] }}"
                                loading="lazy" width="1280" height="800"
                                class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-secondary/80 via-secondary/15 to-transparent"></div>
                            <span class="absolute bottom-3 left-3 inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary text-primary-content shadow-lg">
                                <x-icon name="{{ $item['icon'] }}" class="w-6 h-6" />
                            </span>
                        </div>
                        <div class="p-6 sm:p-7">
                            <h3 class="text-xl font-bold text-base-content">{{ $item['title'] }}</h3>
                            <p class="mt-3 text-base-content/70 leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== QUEM SOMOS ===== --}}
    <section class="bg-base-200">
        <div class="mx-auto max-w-7xl px-4 lg:px-8 py-16 sm:py-20 lg:py-24">
            <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
                <div>
                    <x-site.section-heading
                        index="02"
                        eyebrow="Quem somos"
                        title="Mais de meio século transformando plástico em soluções"
                        subtitle="A Cirna iniciou suas atividades em 1972 na fabricação de moldes e, ao longo dos anos, incorporou a injeção de peças plásticas — tornando-se um parceiro completo para o desenvolvimento de produtos." />
                    <a href="{{ route('site.empresa') }}" wire:navigate
                        class="btn btn-primary text-primary-content mt-8 w-full sm:w-auto gap-2 rounded-full px-6">
                        Conheça a empresa <x-icon name="o-arrow-right" class="w-5 h-5" />
                    </a>

                    <div class="mt-8 overflow-hidden rounded-2xl shadow-lg ring-1 ring-base-300/60">
                        <img src="{{ asset('assets/cirna/site/fabrica.jpg') }}"
                            alt="Interior do parque fabril" loading="lazy" width="1280" height="800"
                            class="w-full h-52 sm:h-64 object-cover">
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach ([
                        ['icon' => 'o-hand-raised', 'title' => 'Respeito', 'desc' => 'Com clientes, colaboradores e fornecedores.'],
                        ['icon' => 'o-shield-check', 'title' => 'Comprometimento', 'desc' => 'Com a qualidade em cada etapa.'],
                        ['icon' => 'o-flag', 'title' => 'Determinação', 'desc' => 'Na busca por resultados positivos.'],
                        ['icon' => 'o-sparkles', 'title' => 'Reputação', 'desc' => 'Preservação do nome da empresa.'],
                    ] as $value)
                        <div class="rounded-2xl bg-base-100 border border-base-300/60 p-5">
                            <x-icon name="{{ $value['icon'] }}" class="w-6 h-6 text-primary" />
                            <h3 class="mt-3 font-bold text-base-content">{{ $value['title'] }}</h3>
                            <p class="mt-1 text-sm text-base-content/60">{{ $value['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ===== QUALIDADE ===== --}}
    <section class="bg-base-100">
        <div class="mx-auto max-w-7xl px-4 lg:px-8 py-16 sm:py-20">
            <div class="rounded-3xl bg-secondary text-secondary-content p-8 sm:p-12 lg:p-16 relative overflow-hidden">
                <div class="absolute inset-0 blueprint opacity-40"></div>
                <div class="absolute -bottom-20 -left-20 w-72 h-72 rounded-full bg-primary/20 blur-3xl"></div>
                <div class="relative grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div class="max-w-2xl">
                        <span class="site-kicker">Qualidade</span>
                        <h2 class="mt-4 text-3xl sm:text-4xl lg:text-[2.6rem] font-extrabold leading-[1.05]">
                            Certificação {{ config('client.certification') }}
                        </h2>
                        <p class="mt-4 text-secondary-content/75 leading-relaxed">
                            Produzimos peças injetadas com qualidade garantida pela melhoria contínua de processos,
                            colaboradores motivados e total atendimento às necessidades dos clientes e aos requisitos
                            aplicáveis.
                        </p>
                    </div>
                    <a href="{{ route('site.qualidade') }}" wire:navigate
                        class="btn btn-primary text-primary-content w-full lg:w-auto gap-2 rounded-full px-6">
                        Política da qualidade <x-icon name="o-arrow-right" class="w-5 h-5" />
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CLIENTES ===== --}}
    <section class="bg-base-200">
        <div class="mx-auto max-w-7xl px-4 lg:px-8 py-16 sm:py-20">
            <x-site.section-heading align="center"
                index="03"
                eyebrow="Clientes"
                title="Empresas que confiam na Cirna"
                subtitle="Fornecemos componentes plásticos para indústrias de referência nos segmentos automotivo, rodoviário e mais." />

            <div class="marquee mt-12">
                <div class="marquee__track gap-4 py-2">
                    @foreach (array_merge($clients, $clients) as $client)
                        <div class="flex items-center justify-center shrink-0 w-44 h-24 rounded-2xl bg-base-100 border border-base-300/60 px-6"
                            aria-hidden="{{ $loop->index >= count($clients) ? 'true' : 'false' }}">
                            <img src="{{ asset('assets/cirna/clientes/'.$client['logo']) }}" alt="{{ $client['name'] }}"
                                loading="lazy" class="client-logo max-h-10 w-auto object-contain">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CTA FINAL ===== --}}
    <section class="bg-base-100">
        <div class="mx-auto max-w-5xl px-4 lg:px-8 py-16 sm:py-20 lg:py-24 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-base-content">
                Vamos desenvolver o seu projeto?
            </h2>
            <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-base-content/70">
                Conte para a gente o que você precisa. Nossa equipe está pronta para encontrar a melhor solução em
                moldes e injeção de plásticos.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('site.contato') }}" wire:navigate
                    class="btn btn-primary text-primary-content w-full sm:w-auto gap-2 rounded-full px-6">
                    Fale com a Cirna <x-icon name="o-arrow-right" class="w-5 h-5" />
                </a>
                <a href="tel:{{ config('client.phone_e164') }}"
                    class="btn btn-outline border-base-300 text-base-content hover:border-primary hover:text-primary w-full sm:w-auto rounded-full px-6 gap-2">
                    <x-icon name="o-phone" class="w-5 h-5" /> {{ config('client.phone') }}
                </a>
            </div>
        </div>
    </section>
</div>
