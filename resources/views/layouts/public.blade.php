<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="cirna" class="site-shell scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description"
        content="{{ $metaDescription ?? 'Cirna Indústria de Plásticos e Moldes — desde 1972 fabricando moldes e injetando peças plásticas com qualidade certificada ISO 9001:2015 em Caxias do Sul/RS.' }}">

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="preload" as="font" type="font/woff2" crossorigin
        href="{{ asset('assets/fonts/archivo-latin.woff2') }}">

    {{-- Fonte display industrial auto-hospedada (Archivo variável). asset() = app origin → CSP font-src 'self'. --}}
    <style nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}">
        @font-face {
            font-family: 'Archivo';
            font-style: normal;
            font-weight: 100 900;
            font-display: swap;
            src: url('{{ asset('assets/fonts/archivo-latin.woff2') }}') format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        @font-face {
            font-family: 'Archivo';
            font-style: normal;
            font-weight: 100 900;
            font-display: swap;
            src: url('{{ asset('assets/fonts/archivo-latin-ext.woff2') }}') format('woff2');
            unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }
    </style>

    <title>{{ isset($title) ? $title . ' — ' . config('client.legal_name') : config('client.legal_name') }}</title>

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col font-sans antialiased bg-base-100 text-base-content">
    <x-site.navbar />

    <main class="flex-1">
        {{ $slot }}
    </main>

    <x-site.footer />
    <x-site.whatsapp-button />

    <x-app-toast />
    @livewireScriptConfig
</body>

</html>
