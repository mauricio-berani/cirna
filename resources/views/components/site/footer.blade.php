@php
    $year = now()->year;
    $links = array_values(array_filter([
        ['route' => 'site.empresa', 'label' => 'Empresa'],
        ['route' => 'site.historico', 'label' => 'Histórico'],
        ['route' => 'site.qualidade', 'label' => 'Qualidade'],
        ['route' => 'site.servicos', 'label' => 'Serviços'],
        \App\Models\Common\Setting::showClientsSection() ? ['route' => 'site.clientes', 'label' => 'Clientes'] : null,
        ['route' => 'site.trabalhe-conosco', 'label' => 'Trabalhe Conosco'],
        ['route' => 'site.contato', 'label' => 'Contatos'],
    ]));
@endphp

<footer class="bg-secondary text-secondary-content/80">
    <div class="mx-auto max-w-7xl px-4 lg:px-8 py-12 lg:py-16">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
            {{-- Marca --}}
            <div class="lg:col-span-1">
                <div class="bg-base-100 inline-flex rounded-lg px-3 py-2">
                    <img src="{{ asset('assets/cirna/cirna-logo.png') }}" alt="Cirna" width="225" height="44" class="h-9 w-auto">
                </div>
                <p class="mt-4 text-sm leading-relaxed text-secondary-content/70">
                    {{ config('client.legal_name') }}. Desde {{ config('client.founded_year') }} desenvolvendo produtos,
                    fabricando moldes e injetando peças plásticas com qualidade certificada.
                </p>
                <span class="mt-4 inline-flex items-center gap-2 text-xs font-semibold text-secondary-content/80">
                    <x-icon name="o-check-badge" class="w-4 h-4 text-primary" />
                    Certificação {{ config('client.certification') }}
                </span>
            </div>

            {{-- Navegação --}}
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-secondary-content">Navegação</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    @foreach ($links as $link)
                        <li>
                            <a href="{{ route($link['route']) }}" wire:navigate class="hover:text-primary transition-colors">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contato --}}
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-secondary-content">Contato</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    <li class="flex items-start gap-3">
                        <x-icon name="o-map-pin" class="w-5 h-5 text-primary shrink-0" />
                        <span>
                            {{ config('client.address') }}<br>
                            {{ config('client.zip') }} — {{ config('client.city') }}/{{ config('client.state') }}
                        </span>
                    </li>
                    <li class="flex items-center gap-3">
                        <x-icon name="o-phone" class="w-5 h-5 text-primary shrink-0" />
                        <a href="tel:{{ config('client.phone_e164') }}" class="hover:text-primary transition-colors">
                            {{ config('client.phone') }}
                        </a>
                    </li>
                    <li class="flex items-center gap-3">
                        <x-icon name="o-envelope" class="w-5 h-5 text-primary shrink-0" />
                        <a href="mailto:{{ config('client.email') }}" class="hover:text-primary transition-colors break-all">
                            {{ config('client.email') }}
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Horário / CTA --}}
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-secondary-content">Atendimento</h3>
                <p class="mt-4 text-sm text-secondary-content/70">
                    Segunda a sexta<br>das 08h às 18h
                </p>
                <a href="{{ route('site.contato') }}" wire:navigate
                    class="btn btn-primary btn-sm mt-5 rounded-full px-5 text-primary-content gap-2">
                    Fale conosco <x-icon name="o-arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </div>

        <div class="mt-12 pt-6 border-t border-secondary-content/15 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-secondary-content/60">
            <span>© {{ $year }} {{ config('client.legal_name') }}. Todos os direitos reservados.</span>
            <a href="{{ route('login') }}" class="hover:text-primary transition-colors">Acesso restrito</a>
        </div>
    </div>
</footer>
