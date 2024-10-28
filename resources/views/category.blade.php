<head>
<title>Categorías</title>
</head>
<body>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Categorías') }}
        </h2>
    </x-slot>

    <div class="py-12" style="background-color: white; padding: 0; margin: 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 categorias">
                    <!-- Título -->
                    <h3 class="text-lg font-semibold mb-4">Administrar Categorías</h3>
                    
                    <!-- Mensaje de éxito -->
                    @if(session('success'))
                        <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Formulario para crear categorías -->
                    <form id="cateForm" action="{{ route('categories.store') }}" method="POST" class="mb-8 space-y-6">
                        @csrf
                        <div>
                            <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; text-size:2px; margin-bottom: 3px;">Agregar Categoría</h6>
    
                            <div class="crear-cate">
                                <input type="text" placeholder="Nombre de la Categoría" name="name" id="name" class="inmov mt-1 block w-full  rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <button type="button" onclick="validateAndOpenModal()" class="btn-crear w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md text-white tracking-wide hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                    Crear Categoría
                                </button>
                            </div>
                        </div>                   
                    </form>


                    <!-- Modal de confirmación para agregar nueva categoría -->
                    <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
                        <div class="flex items-center justify-center h-full">
                            <div class="bg-white p-5 rounded shadow-lg modal">
                                <h3 class="font-bold text-lg" style="left: 0; width: 100px; font-size: 25px;">Confirmación</h3>
                                <p>¿Estás seguro de que deseas agregar esta categoría?</p>
                                <div class="mt-4 flex justify-end">
                                    <button onclick="closeConfirmationModal()" class="btn-c bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancelar</button>
                                    <button id="confirmButton" onclick="document.getElementById('cateForm').submit();" class="btn-crear bg-indigo-600 text-white px-4 py-2 rounded-md ml-2">Confirmar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr style="margin-bottom: 20px; margin-top: 30px;" class="border-t-2 border-gray-300 dark:border-gray-600 my-4">
                    <!-- Lista de categorías existentes -->
                    <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; text-size:2px;">Categorías Existentes:</h6>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($categories as $category)
                            <!-- Tarjeta de categoría -->
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-md card-cate relative"> <!-- Añadir 'relative' aquí -->
                                <img src="{{ asset('images/seguro-de-calidad.png') }}" alt="Imagen de categoría" class="w-full h-auto object-cover rounded-md mb-4">
                                
                                <div class="categoria-name">
                                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $category->name }}</h3>
                                </div>

                                <!-- Formulario de eliminación -->
                                <form id="deleteC-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta categoría?');" class="absolute top-2 right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="openConfirmationModal2('{{ $category->id }}')" class="text-red-600 dark:text-red-400 hover:underline btn-delete">
                                        <img src="{{ asset('images/borrar.png') }}" alt="borrar" width="30px">
                                    </button>
                                </form>

                                <!-- Modal de confirmación para eliminar categoría -->
                                <div id="confirmationModal2" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
                                    <div class="flex items-center justify-center h-full">
                                        <div class="bg-white p-5 rounded shadow-lg modal">
                                            <h3 class="font-bold text-lg" style="left: 0; width: 100px; font-size: 25px;">Confirmación</h3>
                                            <p>¿Estás seguro de que deseas eliminar esta categoría?</p>
                                            <div class="mt-4 flex justify-end">
                                                <button onclick="closeConfirmationModal2()" class="btn-c bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancelar</button>
                                                <button id="confirmButton2" onclick="document.getElementById('deleteC').submit();" class="btn-crear bg-indigo-600 text-white px-4 py-2 rounded-md ml-2">Confirmar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>
                    <script>
                    // Función para validar campos y abrir el modal
                    function validateAndOpenModal() {
                        // Selecciona el formulario
                        const form = document.getElementById('cateForm');

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


                    function openConfirmationModal2(id) {
                        document.getElementById('confirmationModal2').classList.remove('hidden');
                        // Asigna la acción del botón de confirmación al formulario con el ID proporcionado
                        document.getElementById('confirmButton2').onclick = function() {
                            document.getElementById('deleteC-' + id).submit();
                        };
                    }

                    // Función para cerrar el modal de confirmación
                    function closeConfirmationModal2() {
                        document.getElementById('confirmationModal2').classList.add('hidden'); // Oculta el modal
                    }
                </script>
                    <style>
                        .categorias{
                            background-color: white;
                        }

                        .categorias h3{
                            color: black;
                            font-size: 25px;
                            font-weight: bold;
                            text-align: center;
                        }
                        .card-cate{
                            background-color: #EAEAEA;
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
                        }

                        .categoria-name{
                            display: flex;
                            justify-content: center;
                        }

                        .categoria-name h3{
                            color: black;
                            display: flex;
                            align-items: center;
                        }

                        .btn-delete{
                            margin-right: 5px;
                            margin-top: 5px;
                        }

                        .crear-cate{
                            display:flex;
                            align-items:center;
                            margin-bottom: 15px;
                        }

                        .crear-cate input{
                            width: 350px;
                        }

                        .btn-crear{
                            width: 155px;
                            height: 40px;
                            background-color: #2A4B89;
                            margin-left: 20px;
                            margin-top: 3px;
                        }

                        .btn-crear:hover {
                            background-color: #476192; 
                        }

                        .btn-c:hover {
                            background-color: #a2a2a2 !important; 
                        }
                        
                        #confirmationModal {
                            
                            z-index: 9999; /* Asegura que esté en el frente de otros elementos */
                            
                        }

                        #confirmationModal2 {
                            
                            z-index: 9999; /* Asegura que esté en el frente de otros elementos */
                            
                        }

                        .modal{
                            color: black !important;
                            z-index: 10000 !important;
                        }

                        .modal h3{
                            margin-bottom: 20px;
                        }

                    </style>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<footer style="width: 100%; height: 40px; display: flex; justify-content: center; align-items: center; background-color: white; overflow: hidden;">
    <p style="color: gray;">© 2024 - Boston Gas, todos los derechos reservados</p>
</footer>
</body>