<head>
<title>Administrar Productos</title>
</head>
<body>
<x-app-layout>

@if (session('success'))
    <div class="bg-green-500 text-white p-4 mb-4">
        {{ session('success') }}
    </div>
@endif
<div class="py-12" style="padding:0; margin:0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 editar-productos">
                    <h3 class="text-lg font-semibold mb-4">Editar Información del Producto</h3>
                    <!-- Formulario para editar el producto -->
                    <form id="editProductForm" action="{{ route('updateProduct', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf <!-- Protección CSRF -->
                        @method('PUT') <!-- Indica que es una actualización -->
                    <div class="nom-ape">
                        <!-- Campo para el nombre del producto -->
                        <div class="mb-4">
                        <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Nombre del Producto</h6>
                            <input type="text" name="name" id="name" value="{{ $product->name }}" required class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Campo para el precio del producto -->
                        <div class="mb-4 mover">
                        <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Precio</h6>
                            <input type="number" name="price" id="price" value="{{ $product->price }}" required step="0.01" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        
                    </div>
                    <div class="nom-ape">
                        
                        <!-- Campo para la cantidad del producto -->
                        <div class="mb-4">
                        <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Stock</h6>
                            <input type="number" name="stock" id="stock" value="{{ $product->stock }}" required class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        
                        <div class="mb-4 mover">
                        <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Categoría</h6>
                        <select name="category" id="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Selecciona una categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected($product->category == $category->name)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <div class="nom-ape">
                        <!-- Campo para la descripción del producto -->
                        <div class="mb-4">
                        <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Descripción</h6>
                            <textarea name="description" id="description" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $product->description }}</textarea>
                        </div>

                        <!-- Campo para subir una imagen del producto -->
                        <div class="mb-4 mover">
                        <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Imagen (Dejar en blanco para no cambiar).</h6>
                            <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" style="border: solid gray 1px;">
                        </div>

                    </div>
                        <!-- Botón para abrir el modal de confirmación -->
                        <button type="button" onclick="openConfirmationModal()" class="btn-up bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none">
                            Actualizar Producto
                        </button>
                    </form>

                    <!-- Modal de confirmación para la actualización del producto -->
                    <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
                        <div class="flex items-center justify-center h-full">
                            <div class="bg-white p-5 rounded shadow-lg modal">
                                <h3 class="font-bold text-lg" style="left: 0; width: 100px">Confirmación</h3>
                                <p>¿Estás seguro de que deseas editar este producto?</p>
                                <div class="mt-4 flex justify-end">
                                    <button onclick="closeConfirmationModal()" class="btn-c bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancelar</button>
                                    <button id="confirmEditButton" onclick="document.getElementById('editProductForm').submit();" class="btn-up bg-indigo-600 text-white px-4 py-2 rounded-md ml-2">Confirmar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <style>
                    .modal{
                        color: black !important;
                    }
                    .editar-productos{
                        background-color: white;
                    }

                    .editar-productos h3{
                        color: black;
                        font-size: 25px;
                        font-weight: bold;
                        text-align: center;
                        margin-bottom: 25px;
                    }
                    .editar-productos input, select, textarea{
                        color: black !important; 
                    }

                    .nom-ape{
                        display: flex;
                    }

                    .nom-ape input, select, textarea{
                        width: 350px !important;
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
                </style>
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
</x-app-layout>
<footer style="width: 100%; height: 40px; display: flex; justify-content: center; align-items: center; background-color: white; overflow: hidden;">
    <p style="color: gray;">© 2024 - Boston Gas, todos los derechos reservados</p>
</footer>
</body>