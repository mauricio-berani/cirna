@php($whatsapp = config('client.whatsapp'))

@if (! empty($whatsapp))
    <a href="https://wa.me/{{ $whatsapp }}?text={{ rawurlencode('Olá! Gostaria de mais informações sobre os serviços da Cirna.') }}"
        target="_blank" rel="noopener"
        aria-label="Falar no WhatsApp"
        class="fixed bottom-5 right-5 z-50 inline-flex items-center justify-center w-14 h-14 rounded-full bg-success text-success-content shadow-lg hover:scale-105 transition-transform">
        <x-icon name="o-chat-bubble-left-right" class="w-7 h-7" />
    </a>
@endif
