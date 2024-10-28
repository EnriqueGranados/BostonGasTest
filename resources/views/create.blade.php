<head>
<title>Empleados</title>
</head>
<body>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Usuario Empleado') }}
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

    <div class="empleados">
        <h3 class="text-lg font-semibold mb-4">Administrar Empleados</h3>
    </div>
    
    <div class="py-12" style="padding: 0; margin:0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="padding-top: 0; margin-top:0;">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" style="padding-top: 0; margin-top:0; background-color: white;">
                <div class="p-6 text-gray-900 dark:text-gray-100" style="padding-top: 0; margin-top:0;">
                @if (session('success'))
                    <div class="bg-green-500 text-white rounded-lg p-4 mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Crear Empleado</h6>
                    <form id="usersForm" action="{{ route('users.store') }}" method="POST">
                        @csrf

                        
                        <div class="mb-4 name-mail">  
                            <!-- Campo de Nombre -->
                            <input type="text" placeholder="Nombres del Empleado" id="name" name="name" required
                            class="mt-1 block w-full  rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />

                            <!-- Campo de Nombre -->
                            <input type="text" placeholder="Apellidos del Empleado" id="last_name" name="last_name" required
                            class="inmov mt-1 block w-full  rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>


                        <div class="mb-4 name-mail">  
                            <!-- Campo de Nombre -->
                            <input type="tel" placeholder="Teléfono" id="phone_number" name="phone_number" required
                            class="mt-1 block w-full  rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />

                            <!-- Campo de Correo -->
                            <input type="email" placeholder="Correo Electrónico" id="email" name="email" required
                            class="inmov mt-1 block w-full  rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>


                        
                        <div class="mb-4 name-mail">
                            <!-- Campo de Contraseña -->
                            <input type="password" placeholder="Contraseña" id="password" name="password" required
                                   class="mt-1 block w-full  rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        
                            <!-- Campo de Confirmación de Contraseña -->
                            <input type="password" placeholder="Confirmar Contraseña" id="password_confirmation" name="password_confirmation" required
                                   class="inmov mt-1 block w-full  rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>

                        <!-- Botón de Crear -->
                        <button type="button" onclick="validateAndOpenModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 btn-save">
                            Guardar
                        </button>
                    </form>

                    <!-- Modal de confirmación para guardar empleado -->
                    <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
                        <div class="flex items-center justify-center h-full">
                            <div class="bg-white p-5 rounded shadow-lg modal">
                                <h3 class="font-bold text-lg" style="left: 0; width: 100px; font-size: 25px;">Confirmación</h3>
                                <p>¿Estás seguro de que deseas guardar este empleado?</p>
                                <div class="mt-4 flex justify-end">
                                    <button onclick="closeConfirmationModal()" class="btn-c bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancelar</button>
                                    <button id="confirmEditButton" onclick="document.getElementById('usersForm').submit();" class="btn-save bg-indigo-600 text-white px-4 py-2 rounded-md ml-2">Confirmar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr style="margin-bottom: 30px; margin-top: 30px;" class="border-t-2 border-gray-300 dark:border-gray-600 my-4">


                    <!-- Listado de Usuarios Existentes -->
                    <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 5px;">Lista de Empleados</h6>       
                    <table class="min-w-full w-full bg-white dark:bg-gray-800" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">
                        <thead class="the">
                            <tr>
                                <th class="px-6 py-3  text-left text-sm leading-4 text-gray-600 dark:text-gray-300" style="width: 22%; height: 20px;">Nombres</th>
                                <th class="px-6 py-3  text-left text-sm leading-4 text-gray-600 dark:text-gray-300" style="width: 22%; height: 20px;">Apellidos</th>
                                <th class="px-6 py-3  text-left text-sm leading-4 text-gray-600 dark:text-gray-300" style="width: 22%; height: 20px;">Teléfono</th>
                                <th class="px-6 py-3  text-left text-sm leading-4 text-gray-600 dark:text-gray-300" style="width: 24%; height: 20px;">Email</th>
                                <th class="px-6 py-3  text-left text-sm leading-4 text-gray-600 dark:text-gray-300" style="width: 10%; height: 20px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="tbo">
                            @foreach ($users as $user)
                                <tr class="bg-white dark:bg-gray-900">
                                    <td class="px-6 py-4  text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                                    <td class="px-6 py-4  text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->last_name }}</td>
                                    <td class="px-6 py-4  text-sm font-medium text-gray-900 dark:text-gray-100" style="text-align: center;">{{ $user->phone_number }}</td>
                                    <td class="px-6 py-4  text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->email }}</td>
                                    <td class="px-6 py-4  text-sm font-medium" style="height: 20px;">
                                        <!-- Formulario para eliminar usuario -->
                                        <div class="btns">
                                            <form id="deleteUser-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return false;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="openConfirmationModal2({{ $user->id }})" class="text-red-600 hover:text-red-900">
                                                    <img src="{{ asset('images/borrar.png') }}" alt="borrar" width="30px">
                                                </button>
                                            </form>
                                            <!-- Ícono de edición -->
                                            <a href="{{ route('users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">
                                                <img src="{{ asset('images/editar.png') }}" alt="editar" width="30px">
                                            </a>
                                        </div>

                                        <!-- Modal de confirmación para la actualización del producto -->
                                        <div id="confirmationModal2" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
                                            <div class="flex items-center justify-center h-full">
                                                <div class="bg-white p-5 rounded shadow-lg modal">
                                                    <h3 class="font-bold text-lg" style="left: 0; width: 100px; font-size: 25px;">Confirmación</h3>
                                                    <p>¿Estás seguro de que deseas eliminar este empleado?</p>
                                                    <div class="mt-4 flex justify-end">
                                                        <button onclick="closeConfirmationModal2()" class="btn-c bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancelar</button>
                                                        <button id="confirmDeleteButton" onclick="document.getElementById('deleteUser').submit();" class="btn-save bg-indigo-600 text-white px-4 py-2 rounded-md ml-2">Confirmar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                      
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>                  
                </div>
            </div>
        </div>
        <script>
            // Función para validar campos y abrir el modal
            function validateAndOpenModal() {
                // Selecciona el formulario
                const form = document.getElementById('usersForm');

                // Verifica si el formulario es válido
                if (form.checkValidity()) {
                    // Si es válido, muestra el modal
                    openConfirmationModal();
                } else {
                    // Si no es válido, muestra los mensajes de error nativos del navegador
                    form.reportValidity();
                }
            }
            // Función para abrir el modal de confirmación
            function openConfirmationModal() {
                document.getElementById('confirmationModal').classList.remove('hidden'); // Muestra el modal
            }
        
            // Función para cerrar el modal de confirmación
            function closeConfirmationModal() {
                document.getElementById('confirmationModal').classList.add('hidden'); // Oculta el modal
            }


            // Función para abrir el modal de confirmación de eliminación
            function openConfirmationModal2(userId) {
                document.getElementById('confirmationModal2').classList.remove('hidden');
                document.getElementById('confirmDeleteButton').onclick = function() {
                    document.getElementById(`deleteUser-${userId}`).submit();
                };
            }
        
            // Función para cerrar el modal de confirmación
            function closeConfirmationModal2() {
                document.getElementById('confirmationModal2').classList.add('hidden'); // Oculta el modal
            }
        </script>
        <style>
            .empleados{
                background-color: white;
                margin-top: 24px;
            }

            .empleados h3{
                color: black;
                font-size: 25px;
                font-weight: bold;
                text-align: center;
                margin-bottom: 25px;
            }

            .name-mail{
                display: flex;
            }

            .name-mail input{
                width: 350px;
                color: black;
            }

            .inmov{
                margin-left: 20px;
            }

            .btn-save{
                background-color: #2A4B89;
            }

            .btn-save:hover {
                background-color: #476192; 
            }

            .tbo tr{
                background-color: #D9D9D9;
            }
            .tbo td{
                color: black;
                border: solid white 1px;
                height: 30px !important;
                padding: 5px;
                padding-left: 10px;
            }

            .the tr{
                background-color: #2A4B89; 
            }

            .the th{
                color: white;
                border: solid white 1px;
                text-align: center; 
                height: 30px !important;
            }

            .btns{
                display: flex;
                justify-content: space-between;
                padding:0;
                margin-left: 7px;
                margin-right: 7px;
            }

            .btns form{
                padding:0;
                margin: 0;
            }

            .btn-c:hover {
                background-color: #a2a2a2; 
            }

            .modal{
                color: black !important;
            }

            .modal h3{
                margin-bottom: 20px;
            }
            
        </style>
    </div>
</x-app-layout>
<footer style="width: 100%; height: 40px; display: flex; justify-content: center; align-items: center; background-color: white; overflow: hidden;">
    <p style="color: gray;">© 2024 - Boston Gas, todos los derechos reservados</p>
</footer> 
</body>