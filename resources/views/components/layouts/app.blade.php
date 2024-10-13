<!DOCTYPE html>
<html class="h-full" lang="en" lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="darkMode"
    :class="darkMode ? 'dark' : ''">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ringside') }}</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" />
    @vite('resources/js/app.js')

    @livewireStyles
</head>

<body
    x-data="{ sidebarOpen: true }"
    class="antialiased flex h-full text-base text-gray-700 demo1 [--tw-page-bg:#fefefe] [--tw-page-bg-dark:var(--tw-coal-500)] sidebar-fixed header-fixed bg-[--tw-page-bg] dark:bg-[--tw-page-bg-dark]">
    <div class="flex grow">
        <x-sidebar/>

        <x-page.wrapper>
            <x-layouts.partials.header />

            <main class="grow pt-5" role="content">
                {{ $slot }}
            </main>

            <x-layouts.partials.footer />
        </x-page.wrapper>
    </div>

    @livewireScriptConfig
    @livewire('wire-elements-modal')
</body>

</html>
