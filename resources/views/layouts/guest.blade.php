<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased" style="font-family: 'Inter', sans-serif;">
    <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="text-center">
            <h3 class="text-4xl font-bold text-gray-800">SISTEM KEUANGAN</h3>
            <h4 class="text-3xl font-medium text-gray-600 mt-2">Yayasan ASA KITA</h4>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 backdrop-filter backdrop-blur-lg bg-white/50 shadow-lg border border-white/30 rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
