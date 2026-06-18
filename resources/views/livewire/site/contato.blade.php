<div>
    <x-site.page-hero
        eyebrow="Contato"
        title="Fale com a Cirna"
        subtitle="Conte com a nossa equipe para desenvolver produtos, fabricar moldes ou produzir suas peças plásticas."
        :breadcrumbs="[['label' => 'Contato']]" />

    <section class="bg-base-100">
        <div class="mx-auto max-w-7xl px-4 lg:px-8 py-16 sm:py-20">
            <div class="grid gap-10 lg:grid-cols-5">
                {{-- Informações --}}
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-base-content">{{ config('client.legal_name') }}</h2>
                        <p class="mt-2 text-base-content/65">
                            Estamos em {{ config('client.city') }}/{{ config('client.state') }} e atendemos clientes em
                            diversos segmentos da indústria.
                        </p>
                    </div>

                    <ul class="space-y-4">
                        <li class="flex items-start gap-4">
                            <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-primary/10 text-primary shrink-0">
                                <x-icon name="o-map-pin" class="w-6 h-6" />
                            </span>
                            <div>
                                <p class="font-semibold text-base-content">Endereço</p>
                                <p class="text-sm text-base-content/65">
                                    {{ config('client.address') }}<br>
                                    {{ config('client.zip') }} — {{ config('client.city') }}/{{ config('client.state') }}
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-primary/10 text-primary shrink-0">
                                <x-icon name="o-phone" class="w-6 h-6" />
                            </span>
                            <div>
                                <p class="font-semibold text-base-content">Telefone</p>
                                <a href="tel:{{ config('client.phone_e164') }}" class="text-sm text-base-content/65 hover:text-primary transition">
                                    {{ config('client.phone') }}
                                </a>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-primary/10 text-primary shrink-0">
                                <x-icon name="o-envelope" class="w-6 h-6" />
                            </span>
                            <div>
                                <p class="font-semibold text-base-content">E-mail</p>
                                <a href="mailto:{{ config('client.email') }}" class="text-sm text-base-content/65 hover:text-primary transition break-all">
                                    {{ config('client.email') }}
                                </a>
                            </div>
                        </li>
                    </ul>

                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(config('client.maps_query')) }}"
                        target="_blank" rel="noopener"
                        class="flex items-center gap-4 rounded-2xl border border-base-300/70 bg-base-200 p-5 transition hover:border-primary/40">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary text-primary-content shrink-0">
                            <x-icon name="o-map" class="w-6 h-6" />
                        </span>
                        <div>
                            <p class="font-semibold text-base-content">Como chegar</p>
                            <p class="text-sm text-base-content/65">Ver localização no Google Maps</p>
                        </div>
                        <x-icon name="o-arrow-up-right" class="w-5 h-5 text-base-content/40 ms-auto" />
                    </a>

                    <a href="{{ route('site.trabalhe-conosco') }}" wire:navigate
                        class="flex items-center gap-4 rounded-2xl border border-base-300/70 bg-base-200 p-5 transition hover:border-primary/40">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-secondary text-secondary-content shrink-0">
                            <x-icon name="o-briefcase" class="w-6 h-6" />
                        </span>
                        <div>
                            <p class="font-semibold text-base-content">Trabalhe Conosco</p>
                            <p class="text-sm text-base-content/65">Envie seu currículo para o nosso RH</p>
                        </div>
                        <x-icon name="o-arrow-right" class="w-5 h-5 text-base-content/40 ms-auto" />
                    </a>

                    @if (config('client.harassment_channel_url'))
                        <a href="{{ config('client.harassment_channel_url') }}" target="_blank" rel="noopener"
                            class="flex items-center gap-4 rounded-2xl border border-base-300/70 bg-base-200 p-5 transition hover:border-primary/40">
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-secondary text-secondary-content shrink-0">
                                <x-icon name="o-shield-exclamation" class="w-6 h-6" />
                            </span>
                            <div>
                                <p class="font-semibold text-base-content">Canal de Assédio</p>
                                <p class="text-sm text-base-content/65">Registre uma denúncia com sigilo e segurança</p>
                            </div>
                            <x-icon name="o-arrow-up-right" class="w-5 h-5 text-base-content/40 ms-auto" />
                        </a>
                    @endif
                </div>

                {{-- Formulário --}}
                <div class="lg:col-span-3">
                    <div class="rounded-3xl border border-base-300/70 bg-base-100 p-6 sm:p-8 shadow-sm">
                        <h2 class="text-xl font-bold text-base-content">Envie sua mensagem</h2>
                        <p class="mt-1 text-sm text-base-content/60">
                            Preencha o formulário abaixo e retornaremos o mais breve possível.
                        </p>

                        <x-form wire:submit.prevent="send" class="mt-6">
                            {{-- Honeypot anti-spam (oculto para humanos) --}}
                            <div aria-hidden="true" class="absolute -left-[9999px] top-auto w-px h-px overflow-hidden">
                                <label>Não preencha este campo
                                    <input type="text" wire:model="form.website" tabindex="-1" autocomplete="off">
                                </label>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <x-input label="{{ __('site.contact.fields.name') }} *" wire:model="form.name"
                                    placeholder="Seu nome" error-class="text-error text-sm mt-1" />

                                <x-input label="{{ __('site.contact.fields.email') }} *" type="email" wire:model="form.email"
                                    placeholder="voce@empresa.com" error-class="text-error text-sm mt-1" />

                                <x-input label="{{ __('site.contact.fields.phone') }}" wire:model="form.phone"
                                    placeholder="(54) 99999-9999" error-class="text-error text-sm mt-1"
                                    x-data x-mask:dynamic="$input.length > 14 ? '(99) 9 9999-9999' : '(99) 9999-9999'" />

                                <x-select label="{{ __('site.contact.fields.sector') }}" :options="$sectorOptions"
                                    option-value="value" option-label="label" wire:model="form.sector"
                                    error-class="text-error text-sm mt-1" />
                            </div>

                            <x-textarea label="{{ __('site.contact.fields.message') }} *" wire:model="form.message"
                                placeholder="Descreva o seu projeto ou necessidade" rows="5"
                                error-class="text-error text-sm mt-1" class="mt-4" />

                            <x-slot:actions>
                                <x-button label="{{ __('site.contact.submit') }}" type="submit"
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
