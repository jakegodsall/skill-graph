<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-gray-700 font-sans antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center py-6">
            <!-- Content -->
            <div class="w-full max-w-sm px-6 py-8 bg-white shadow-md rounded-lg sm:max-w-md ">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
