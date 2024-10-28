<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900" style="background-color: white;">
            @include('layouts.navigation')
            <div class="nav">
            <nav class="navbar">
                <ul>
                    <!-- <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <img src="{{ asset('images/dashboard.png') }}" alt="Dashboard Icon">
                            <span>Dashboard</span>
                        </a>
                    </li> -->
                    <li class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
                        <a href="{{ route('products.index') }}">
                            <img src="{{ asset('images/productos.png') }}" alt="Productos Icon">
                            <span>Productos</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('cart.show') ? 'active' : '' }}">
                        <a href="{{ route('cart.show') }}">
                            <img src="{{ asset('images/carrito2.png') }}" alt="Carrito Icon">
                            <span>Carrito</span>
                        </a>
                    </li>
                    <!-- Visible solo para admin: Reporte de Ventas -->
                    @if (Auth::user() && Auth::user()->role === 'admin')
                    <li class="{{ request()->routeIs('reporte.index') || request()->routeIs('reportes.generate') ? 'active' : '' }}">
                        <a href="{{ route('reporte.index') }}">
                            <img src="{{ asset('images/reporte.png') }}" alt="Productos Admin Icon">
                            <span>Reporte de Ventas</span>
                        </a>
                    </li>
                    @endif
                    @if (Auth::user() && Auth::user()->role === 'employee')
                    <li class="{{ request()->routeIs('sales.index') ? 'active' : '' }}">
                        <a href="{{ route('sales.index') }}">
                            <img src="{{ asset('images/editar-vent.png') }}" alt="Productos Admin Icon">
                            <span>Visualizar Ventas</span>
                        </a>
                    </li>
                    @endif
                    @if (Auth::user() && Auth::user()->role === 'admin')
                    <li class="{{ request()->routeIs('sales.index') ? 'active' : '' }}">
                        <a href="{{ route('sales.index') }}">
                            <img src="{{ asset('images/editar-vent.png') }}" alt="Productos Admin Icon">
                            <span>Administrar Ventas</span>
                        </a>
                    </li>
                    @endif
                    <!-- Visible solo para admin: Administrar Productos - Categorís - Empleados-->
                    @if (Auth::user() && Auth::user()->role === 'admin')
                    <li class="{{ request()->routeIs('editProduct') || request()->routeIs('editOneProduct') ? 'active' : '' }}">
                        <a href="{{ route('editProduct') }}">
                            <img src="{{ asset('images/product-edit.png') }}" alt="Productos Admin Icon">
                            <span>Administrar Productos</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('categories.index') ? 'active' : '' }}">
                        <a href="{{ route('categories.index') }}">
                            <img src="{{ asset('images/cate.png') }}" alt="Productos Admin Icon">
                            <span>Categorias</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('users.create') || request()->routeIs('users.edit') ? 'active' : '' }}">
                        <a href="{{ route('users.create') }}">
                            <img src="{{ asset('images/emple.png') }}" alt="Empleados Icon">
                            <span>Empleados</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>
            </div>
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <style>

            .nav{
                width: 100%;
                display: flex;
                justify-content: center;
                padding: 0;
                margin: 0;
                background-color: #ffaf78;
                
            }

            .navbar {
                padding: 15px;
                display: flex;
                justify-content: space-between; /* Espacio entre cada botón */
                width: 100%; /* Ocupa el 100% del ancho */
                padding: 0;
                margin: 0;
            }

            .navbar ul {
                list-style-type: none;
                padding: 0;
                margin: 0;
                display: flex;
                flex-direction: row;
                width: 100%;
            }

            .navbar li {
                text-align: center;
                flex-grow: 1; /* Cada botón ocupa el mismo espacio */
            }

            .navbar li a {
                display: flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                color: #333;
                font-weight: bold;
                padding: 10px;
                transition: background-color 0.3s ease;
                width: 100%;
            }

            .navbar li a img {
                margin-right: 10px;
                width: 30px;
                height: 30px;
            }

            .navbar li.active a {
                background-color: #fe9584;
                color:black;
            }

            .navbar li a:hover {
                background-color: #ddd;
            }
        </style>
    </body>
</html>
