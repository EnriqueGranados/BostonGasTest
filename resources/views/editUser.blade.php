<head>
<title>Empleados</title>
</head>
<body>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Usuario') }}
        </h2>
    </x-slot>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-500 text-white p-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="py-12" style="padding:0; margin:0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 empleados">
                    <h3 class="text-lg font-semibold mb-4">Editar Información del Empleado</h3>
                    <form id="editUser" action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="nom-ape">
                            <!-- Campo de Nombre -->
                            <div class="mb-4">
                            <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Nombre</h6>
                                <input type="text" id="name" name="name" value="{{ $user->name }}" required
                                    class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>

                            <!-- Campo de Apellido -->
                            <div class="mb-4 mover">
                            <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Apellido</h6>
                                <input type="text" id="last_name" name="last_name" value="{{ $user->last_name }}"
                                    class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                        </div>
                        
                            <div class="nom-ape">
                            <!-- Campo de Dirección -->
                            <div class="mb-4">
                            <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Dirección</h6>
                                <input type="text" id="address" name="address" value="{{ $user->address }}"
                                    class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>

                            <!-- Campo de Número de Teléfono -->
                            <div class="mb-4 mover">
                            <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Teléfono</h6>
                                <input type="text" id="phone_number" name="phone_number" value="{{ $user->phone_number }}"
                                    class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>

                        </div>

                        <div class="nom-ape">
                            <!-- Campo de Fecha de Nacimiento -->
                            <div class="mb-4">
                            <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Fecha de Nacimiento</h6>
                                <input type="date" id="birth_date" name="birth_date" value="{{ $user->birth_date }}"
                                    class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>

                            <!-- Campo de Género -->
                            <div class="mb-4 mover">
                            <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Genero</h6>
                                <select id="gender" name="gender" class="mt-1 block w-full  rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="male" {{ $user->gender === 'male' ? 'selected' : '' }}>Masculino</option>
                                    <option value="female" {{ $user->gender === 'female' ? 'selected' : '' }}>Femenino</option>
                                    <option value="other" {{ $user->gender === 'other' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>
                        </div>
                        <!-- Botón de Actualizar -->
                        <button type="button" onclick="openConfirmationModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 btn-up">
                            Actualizar Empleado
                        </button>
                    </form>

                    <!-- Modal de confirmación para la actualización del producto -->
                    <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
                        <div class="flex items-center justify-center h-full">
                            <div class="bg-white p-5 rounded shadow-lg modal">
                                <h3 class="font-bold text-lg" style="left: 0; width: 100px">Confirmación</h3>
                                <p>¿Estás seguro de que deseas editar este empleado?</p>
                                <div class="mt-4 flex justify-end">
                                    <button onclick="closeConfirmationModal()" class="btn-c bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancelar</button>
                                    <button id="confirmEditButton" onclick="document.getElementById('editUser').submit();" class="btn-up bg-indigo-600 text-white px-4 py-2 rounded-md ml-2">Confirmar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    // Función para abrir el modal de confirmación
                    function openConfirmationModal() {
                        document.getElementById('confirmationModal').classList.remove('hidden'); // Muestra el modal
                    }
                
                    // Función para cerrar el modal de confirmación
                    function closeConfirmationModal() {
                        document.getElementById('confirmationModal').classList.add('hidden'); // Oculta el modal
                    }
                </script>
                <style>
                    .empleados{
                        background-color: white;
                    }

                    .empleados h3{
                        color: black;
                        font-size: 25px;
                        font-weight: bold;
                        text-align: center;
                        margin-bottom: 25px;
                    }
                    .empleados input, select{
                        color: black;
                    }

                    .nom-ape{
                        display: flex;
                    }

                    .nom-ape input, select{
                        width: 350px;
                    }

                    .mover{
                        margin-left: 20px;
                    }

                    .btn-up{
                        background-color: #2A4B89;
                    }

                    .btn-up:hover {
                        background-color: #476192; 
                    }

                    .btn-c:hover {
                        background-color: #a2a2a2; 
                    }

                    .modal{
                        color: black !important;
                    }
                </style>
            </div>
        </div>
    </div>
</x-app-layout>
<footer style="width: 100%; height: 40px; display: flex; justify-content: center; align-items: center; background-color: white; overflow: hidden;">
    <p style="color: gray;">© 2024 - Boston Gas, todos los derechos reservados</p>
</footer> 
</body>