<div>
    <x-site.page-hero
        eyebrow="Empresa"
        title="Histórico"
        subtitle="Uma trajetória de evolução constante — da ferramentaria à solução completa em injeção de plásticos."
        :breadcrumbs="[['label' => 'Empresa', 'route' => 'site.empresa'], ['label' => 'Histórico']]">
        <div class="mt-8">
            <x-site.empresa-subnav />
        </div>
    </x-site.page-hero>

    <section class="bg-base-100">
        <div class="mx-auto max-w-4xl px-4 lg:px-8 py-16 sm:py-20">
            <div class="prose-none text-lg leading-relaxed text-base-content/75 space-y-5">
                <p>
                    A <strong class="text-base-content">Cirna</strong> iniciou suas atividades no ano de
                    <strong class="text-base-content">1972</strong>, atuando exclusivamente no ramo de fabricação de
                    moldes para injeção de peças plásticas.
                </p>
                <p>
                    Com o decorrer dos anos, percebeu que o mercado indicava a necessidade de expandir seu ramo de
                    atuação e incorporou ao seu negócio a injeção de peças plásticas.
                </p>
                <p>
                    Há mais de <strong class="text-base-content">{{ $yearsInMarket }} anos</strong> a Cirna atua na
                    fabricação de moldes e na injeção de peças plásticas utilizadas como componentes para diversos
                    segmentos de mercado. Nestes anos todos, nos empenhamos para que o nosso cliente encontre em nossa
                    empresa a solução para suas necessidades no desenvolvimento de produtos, fabricação de moldes e
                    injeção de peças plásticas.
                </p>
            </div>

            {{-- Linha do tempo --}}
            <ol class="mt-14 relative border-s-2 border-base-300">
                @foreach ([
                    ['year' => '1972', 'title' => 'Fundação', 'desc' => 'Início das atividades na fabricação de moldes para injeção de peças plásticas.'],
                    ['year' => 'Expansão', 'title' => 'Injeção de plásticos', 'desc' => 'A empresa incorpora a injeção de peças plásticas ao seu negócio, ampliando a atuação.'],
                    ['year' => 'Hoje', 'title' => 'Solução completa', 'desc' => 'Mais de '.$yearsInMarket.' anos integrando desenvolvimento, ferramentaria e injeção para diversos segmentos.'],
                ] as $marco)
                    <li class="ms-8 pb-10 last:pb-0">
                        <span class="absolute -start-[11px] flex items-center justify-center w-5 h-5 rounded-full bg-primary ring-4 ring-base-100"></span>
                        <span class="inline-block rounded-full bg-primary/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-primary">
                            {{ $marco['year'] }}
                        </span>
                        <h3 class="mt-3 text-xl font-bold text-base-content">{{ $marco['title'] }}</h3>
                        <p class="mt-2 text-base-content/70">{{ $marco['desc'] }}</p>
                    </li>
                @endforeach
            </ol>

            <div class="mt-12 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('site.servicos') }}" wire:navigate
                    class="btn btn-primary text-primary-content w-full sm:w-auto gap-2 rounded-full px-6">
                    Ver nossos serviços <x-icon name="o-arrow-right" class="w-5 h-5" />
                </a>
                <a href="{{ route('site.contato') }}" wire:navigate
                    class="btn btn-outline border-base-300 text-base-content hover:border-primary hover:text-primary w-full sm:w-auto rounded-full px-6">
                    Fale conosco
                </a>
            </div>
        </div>
    </section>

    <x-site.media-band
        image="cirna-parque-injetoras.jpg"
        eyebrow="Desde 1972"
        title="Tradição que evolui com a indústria"
        text="Da ferramentaria à injeção em escala, mais de cinco décadas aperfeiçoando processos e atendendo novos mercados." />
</div>
