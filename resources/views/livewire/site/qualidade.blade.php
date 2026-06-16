<div>
    <x-site.page-hero
        eyebrow="Empresa"
        title="Qualidade"
        subtitle="Compromisso com a melhoria contínua e a satisfação do cliente, reconhecido por certificação internacional."
        :breadcrumbs="[['label' => 'Empresa', 'route' => 'site.empresa'], ['label' => 'Qualidade']]">
        <div class="mt-8">
            <x-site.empresa-subnav />
        </div>
    </x-site.page-hero>

    <section class="bg-base-100">
        <div class="mx-auto max-w-7xl px-4 lg:px-8 py-16 sm:py-20">
            <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-1.5 text-sm font-bold text-primary">
                        <x-icon name="o-check-badge" class="w-5 h-5" /> {{ config('client.certification') }}
                    </span>
                    <h2 class="mt-5 text-2xl sm:text-3xl font-extrabold text-base-content">
                        Uma empresa certificada
                    </h2>
                    <p class="mt-4 text-lg leading-relaxed text-base-content/75">
                        A Cirna é certificada pela norma <strong class="text-base-content">{{ config('client.certification') }}</strong>,
                        o que demonstra o seu comprometimento com a qualidade dos produtos que fabrica.
                    </p>

                    <div class="mt-8 rounded-2xl border border-base-300/70 bg-base-200 p-6 sm:p-8">
                        <h3 class="text-lg font-bold text-base-content">Política da Qualidade</h3>
                        <p class="mt-3 text-base-content/75 leading-relaxed">
                            Produzir peças injetadas com qualidade garantida pela melhoria contínua de processos,
                            contando com colaboradores motivados, buscando a satisfação e o pleno atendimento às
                            necessidades dos clientes e aos requisitos aplicáveis.
                        </p>
                    </div>

                    @if ($certificateUrl)
                        <a href="{{ $certificateUrl }}" target="_blank" rel="noopener"
                            class="btn btn-primary text-primary-content mt-6 w-full sm:w-auto gap-2 rounded-full px-6">
                            <x-icon name="o-document-arrow-down" class="w-5 h-5" />
                            {{ __('site.quality.view_certificate') }}
                        </a>
                    @endif
                </div>

                {{-- Pilares --}}
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach ([
                        ['icon' => 'o-arrow-trending-up', 'title' => 'Melhoria contínua', 'desc' => 'Aprimoramento constante dos processos produtivos.'],
                        ['icon' => 'o-users', 'title' => 'Colaboradores motivados', 'desc' => 'Pessoas engajadas em fazer sempre o melhor.'],
                        ['icon' => 'o-face-smile', 'title' => 'Satisfação do cliente', 'desc' => 'Pleno atendimento às necessidades de quem confia na Cirna.'],
                        ['icon' => 'o-clipboard-document-check', 'title' => 'Requisitos aplicáveis', 'desc' => 'Conformidade com normas e exigências do setor.'],
                    ] as $pilar)
                        <div class="rounded-2xl border border-base-300/60 bg-base-100 p-6">
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 text-primary">
                                <x-icon name="{{ $pilar['icon'] }}" class="w-6 h-6" />
                            </span>
                            <h3 class="mt-4 font-bold text-base-content">{{ $pilar['title'] }}</h3>
                            <p class="mt-2 text-sm text-base-content/65">{{ $pilar['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <x-site.media-band
        image="precisao.jpg"
        eyebrow="Precisão"
        title="Controle em cada detalhe"
        text="Da fabricação do molde à peça injetada, o compromisso com a qualidade está presente em todas as etapas." />
</div>
