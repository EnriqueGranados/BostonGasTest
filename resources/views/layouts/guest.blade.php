<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Iniciar Sesión</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body{
                font-family: "Varela Round", sans-serif !important;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased" style="padding: 0; margin: 0;">
    <header>
        <div class="navbar" style="height: 80px; width: 100%; display: flex; justify-content: center; background-color: #D9D9D9;">
            <a href="{{ route('welcome') }}"> <!-- Ruta a la página de inicio -->
                <img class="logo-nav" src="{{ asset('images/Logo.png') }}" alt="logo"  style="height: 50px; margin-top: 15px;">
            </a>
        </div>
    </header>

        <div class=" flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900" style="background-color: white; padding: 0; margin: 50px;">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg" style="background-color: #D1D1D1; padding-top: 35px;">
                {{ $slot }}
            </div>
        </div>

        <footer style="width: 100%; display: flex; justify-content: center;">
            <p style="color: gray;">© 2024 - Boston Gas, todos los derechos reservados</p>
        </footer>
    </body>
</html>
