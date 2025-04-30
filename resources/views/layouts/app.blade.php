<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col overflow-x-hidden">

    <!-- Navbar -->
    @include('components.partials.navbar')

    <!-- Kontainer Utama -->
    <div class="flex-grow mt-4 mb-4 sm:mt-24 mx-4 sm:mx-[10%]">
        {{ $slot }}
    </div>

    <!-- Footer -->
    @include('components.partials.footer')

    <!-- Livewire Scripts -->
    @livewireScripts
</body>

</html>
