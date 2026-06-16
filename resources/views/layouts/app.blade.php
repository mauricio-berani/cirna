<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' . config('client.name') : config('client.name') }}</title>

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-base-200 text-base-content">
    <livewire:common.navigation.navbar-component />
    <div class="shadow bg-primary"></div>

    <x-main full-width>
        <x-slot:sidebar drawer="main-drawer" class="bg-base-100 border-r border-base-300/80">
            <div @toggle-sidebar.window="toggle()">
                <livewire:common.navigation.sidebar-component />
            </div>
        </x-slot:sidebar>

        <x-slot:content class="bg-base-200">
            {{ $slot }}
        </x-slot:content>
    </x-main>

    <x-app-toast />
    @livewireScriptConfig
</body>

</html>
