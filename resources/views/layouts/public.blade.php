<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="cirna" class="site-shell scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description"
        content="{{ $metaDescription ?? 'Cirna Indústria de Plásticos e Moldes — desde 1972 fabricando moldes e injetando peças plásticas com qualidade certificada ISO 9001:2015 em Caxias do Sul/RS.' }}">

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

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
