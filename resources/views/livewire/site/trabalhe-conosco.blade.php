<div>
    <x-site.page-hero
        eyebrow="Trabalhe Conosco"
        title="Faça parte da Cirna"
        subtitle="Buscamos pessoas comprometidas com a qualidade e a vontade de crescer junto com a gente. Envie seu currículo e venha construir soluções em plásticos e moldes."
        :breadcrumbs="[['label' => 'Trabalhe Conosco']]" />

    <section class="bg-base-100">
        <div class="mx-auto max-w-7xl px-4 lg:px-8 py-16 sm:py-20">
            <div class="grid gap-10 lg:grid-cols-5">
                {{-- Por que trabalhar na Cirna --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="overflow-hidden rounded-2xl shadow-lg ring-1 ring-base-300/60">
                        <img src="{{ asset('assets/cirna/site/ferramentaria.jpg') }}"
                            alt="Usinagem de precisão na ferramentaria da Cirna" loading="lazy"
                            width="1280" height="800" class="w-full h-52 sm:h-60 object-cover">
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-base-content">Por que a Cirna?</h2>
                        <p class="mt-2 text-base-content/65">
                            Mais de cinco décadas de tradição na indústria de Caxias do Sul, com ambiente que valoriza
                            pessoas, qualidade e desenvolvimento.
                        </p>
                    </div>

                    <ul class="space-y-4">
                        @foreach ([
                            ['icon' => 'o-academic-cap', 'title' => 'Aprendizado constante', 'desc' => 'Convivência com ferramentaria, injeção e desenvolvimento de produtos.'],
                            ['icon' => 'o-users', 'title' => 'Time que se respeita', 'desc' => 'Respeito a colaboradores, clientes e fornecedores é um princípio nosso.'],
                            ['icon' => 'o-shield-check', 'title' => 'Qualidade no DNA', 'desc' => 'Empresa certificada ISO 9001:2015.'],
                        ] as $benefit)
                            <li class="flex items-start gap-4">
                                <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-primary/10 text-primary shrink-0">
                                    <x-icon name="{{ $benefit['icon'] }}" class="w-6 h-6" />
                                </span>
                                <div>
                                    <p class="font-semibold text-base-content">{{ $benefit['title'] }}</p>
                                    <p class="text-sm text-base-content/65">{{ $benefit['desc'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="rounded-2xl border border-base-300/70 bg-base-200 p-5 text-sm text-base-content/70">
                        <x-icon name="o-lock-closed" class="w-5 h-5 text-primary inline" />
                        Seus dados e currículo são tratados com confidencialidade e usados apenas para fins de
                        recrutamento.
                    </div>
                </div>

                {{-- Formulário --}}
                <div class="lg:col-span-3">
                    <div class="rounded-3xl border border-base-300/70 bg-base-100 p-6 sm:p-8 shadow-sm">
                        <h2 class="text-xl font-bold text-base-content">Envie sua candidatura</h2>
                        <p class="mt-1 text-sm text-base-content/60">
                            Preencha os dados e anexe seu currículo em PDF (até 5 MB).
                        </p>

                        <x-form wire:submit.prevent="send" class="mt-6">
                            {{-- Honeypot anti-spam --}}
                            <div aria-hidden="true" class="absolute -left-[9999px] top-auto w-px h-px overflow-hidden">
                                <label>Não preencha este campo
                                    <input type="text" wire:model="form.website" tabindex="-1" autocomplete="off">
                                </label>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <x-input label="{{ __('fields.name') }} *" wire:model="form.name"
                                    placeholder="Seu nome completo" error-class="text-error text-sm mt-1" />

                                <x-input label="{{ __('fields.email') }} *" type="email" wire:model="form.email"
                                    placeholder="voce@email.com" error-class="text-error text-sm mt-1" />

                                <x-input label="{{ __('fields.phone') }}" wire:model="form.phone"
                                    placeholder="(54) 99999-9999" error-class="text-error text-sm mt-1"
                                    x-data x-mask:dynamic="$input.length > 14 ? '(99) 9 9999-9999' : '(99) 9999-9999'" />

                                <x-select label="{{ __('fields.area') }} *" :options="$areaOptions"
                                    option-value="value" option-label="label" wire:model="form.area"
                                    error-class="text-error text-sm mt-1" />
                            </div>

                            <div class="mt-4">
                                <x-file label="{{ __('fields.resume') }} *" wire:model="form.resume"
                                    accept="application/pdf"
                                    hint="{{ __('site.careers.resume_hint') }}"
                                    error-class="text-error text-sm mt-1" />

                                <div wire:loading wire:target="form.resume" class="mt-2 text-sm text-base-content/60">
                                    <x-icon name="o-arrow-path" class="w-4 h-4 inline animate-spin" />
                                    {{ __('site.careers.uploading') }}
                                </div>
                            </div>

                            <x-slot:actions>
                                <x-button label="{{ __('site.careers.submit') }}" type="submit"
                                    class="btn-primary text-primary-content w-full sm:w-auto rounded-full px-6" spinner="send"
                                    icon-right="o-paper-airplane" />
                            </x-slot:actions>
                        </x-form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
