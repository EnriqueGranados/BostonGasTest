<head>
<title>Administrar Productos</title>
</head>
<body>
<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Productos') }}
        </h2>
    </x-slot>

    <div class="py-12" style="background-color: white; padding: 0; margin: 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 productos">
                    <h3 class="text-lg font-semibold mb-4">Administrar Productos</h3>

                    @if (session('success'))
                        <div class="bg-green-500 text-white rounded-lg p-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="agregar">
                         <!-- Formulario para agregar un producto -->
                        <form id="productForm" action="{{ route('storeProduct') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="nom-ape">
                        <!-- Campo para el nombre del producto -->
                        <div class="mb-4">
                        <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Nombre del Producto</h6>
                            <input type="text" name="name" id="name" required class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Campo para el precio del producto -->
                        <div class="mb-4 mover">
                        <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Precio</h6>
                            <input type="number" name="price" id="price" required step="0.01" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        
                    </div>
                    <div class="nom-ape">
                        
                        <!-- Campo para la cantidad del producto -->
                        <div class="mb-4">
                        <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Stock</h6>
                            <input type="number" name="stock" id="stock" required class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        
                        <div class="mb-4 mover">
                        <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Categoría</h6>
                        <select name="category" id="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="">Selecciona una categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
                    <div class="nom-ape">
                        <!-- Campo para la descripción del producto -->
                        <div class="mb-4">
                        <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Descripción</h6>
                            <textarea name="description" id="description" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>

                        <!-- Campo para subir una imagen del producto -->
                        <div class="mb-4 mover">
                        <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Imagen</h6>
                            <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" style="border: solid gray 1px;">
                        </div>

                    </div>
                            
                            <!-- Botón para abrir el modal de confirmación antes de agregar el producto -->
                            <button type="button" onclick="validateAndOpenModal()" class="btn-up bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none">
                                Agregar Producto
                            </button>
                        </form>

                        <!-- Modal de confirmación para la actualización del producto -->
                        <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
                            <div class="flex items-center justify-center h-full">
                                <div class="bg-white p-5 rounded shadow-lg modal">
                                    <h3 class="font-bold text-lg" style="left: 0; width: 100px">Confirmación</h3>
                                    <p>¿Estás seguro de que deseas agregar este producto?</p>
                                    <div class="mt-4 flex justify-end">
                                        <button onclick="closeConfirmationModal()" class="btn-c bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancelar</button>
                                        <button id="confirmButton" onclick="document.getElementById('productForm').submit();" class="btn-up bg-indigo-600 text-white px-4 py-2 rounded-md ml-2">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>

                    <hr style="margin-bottom: 30px; margin-top: 30px;" class="border-t-2 border-gray-300 dark:border-gray-600 my-4">
                    <!-- Barra de búsqueda -->
                    <div class="mb-4 flex items-center">
                        <!-- Selector de filtros -->
                        <select id="filter" class="p-2 border rounded text-black filtro">
                            <option value="">Filtros de Búsqueda</option>
                            <option value="a-z">De A a la Z</option>
                            <option value="z-a">De Z a la A</option>
                            <option value="stock-high-low">Mayor Stock</option>
                            <option value="stock-low-high">Menor Stock</option>
                            <option value="category">Categoría</option>
                            <option value="price-low-high">Precio Ascendente</option>
                            <option value="price-high-low">Precio Descendente</option>
                        </select>

                        <!-- Barra de búsqueda -->
                        <input type="text" id="search" placeholder="Buscar Producto " class="w-full p-2 border rounded text-black barra-bus">
                        <!-- Botón de búsqueda -->
                        <button id="searchButton" class="ml-2 p-2 bg-gray-200 rounded search-btn">
                            <img src="{{ asset('images/lupa.png') }}" alt="Buscar" height="35px" width="35px" class="img-lupa">
                        </button>
                    </div>

                    <!-- Lista de productos como tarjetas -->
                    <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Resultados</h6>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="productList">
                        @foreach($products as $product)
                        <div class="bg-white p-4 rounded-lg shadow-lg relative card-product" 
                            data-name="{{ $product->name }}" 
                            data-stock="{{ $product->stock }}" 
                            data-price="{{ $product->price }}" 
                            data-category="{{ $product->category }}">
                            <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-40 object-cover rounded-md">
                            <h4 class="font-semibold text-md mt-2 product-name">{{ $product->name }}</h4>
                            <p class="text-gray-600 dark:text-gray-400 product-description">{{ $product->description }}</p>

                            <div class="uni-cat">
                                @if($product->stock > 10)
                                    <p class="mt-2 product-unit">{{ $product->stock }} Unidades</p>
                                @elseif($product->stock > 0 && $product->stock <= 10)
                                    <p class="mt-2 text-green-500" style="color: #ff6800;">{{ $product->stock }} Unidades</p>
                                @else
                                    <p class="mt-2 text-red-500">Agotado</p>
                                @endif
                                <p class="categoria">{{ $product->category }}</p>
                                <p class="font-bold text-green-500 product-price">${{ $product->price }}</p>
                            </div>
                            
                            <div class="absolute top-2 right-2 flex flex-col space-y-2">
                                <!-- Enlace para editar el producto -->
                                <a href="{{ route('editOneProduct', $product->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <img src="{{ asset('images/editar.png') }}" alt="editar" height="27px" width="27px" class="img-carrito">
                                </a>
                                <!-- Formulario para eliminar el producto -->
                                <form id="deleteP-{{ $product->id }}" action="{{ route('deleteProduct', $product->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="openConfirmationModal2('{{ $product->id }}')" class="text-red-500 hover:text-red-700">
                                        <img src="{{ asset('images/borrar.png') }}" alt="borrar" height="27px" width="27px" class="img-carrito">
                                    </button>
                                </form>

                                <!-- Modal de confirmación para eliminar categoría -->
                                <div id="confirmationModal2" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
                                    <div class="flex items-center justify-center h-full">
                                        <div class="bg-white p-5 rounded shadow-lg modal">
                                            <h3 class="font-bold text-lg" style="left: 0; width: 100px; font-size: 25px;">Confirmación</h3>
                                            <p>¿Estás seguro de que deseas eliminar este producto?</p>
                                            <div class="mt-4 flex justify-end">
                                                <button onclick="closeConfirmationModal2()" class="btn-c bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancelar</button>
                                                <button id="confirmButton2" onclick="document.getElementById('deleteP').submit();" class="btn-up bg-indigo-600 text-white px-4 py-2 rounded-md ml-2">Confirmar</button>
                                            </div>
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
                            const form = document.getElementById('productForm');

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

                        // Funcionalidad de búsqueda en las tarjetas de productos
                        document.getElementById('search').addEventListener('keyup', function() {
                            var value = this.value.toLowerCase();
                            var products = document.querySelectorAll('#productList > div');

                            products.forEach(function(product) {
                                var isVisible = product.innerText.toLowerCase().includes(value);
                                product.style.display = isVisible ? 'block' : 'none';
                            });
                        });

                        // Función para ordenar los productos según el filtro seleccionado
                        document.getElementById('filter').addEventListener('change', function() {
                            var filter = this.value;
                            var products = Array.from(document.querySelectorAll('#productList > div')).filter(product => product.style.display !== 'none');

                            // Aplicar el filtro correspondiente
                            products.sort(function(a, b) {
                                var nameA = a.getAttribute('data-name').toLowerCase();
                                var nameB = b.getAttribute('data-name').toLowerCase();
                                var stockA = parseInt(a.getAttribute('data-stock'));
                                var stockB = parseInt(b.getAttribute('data-stock'));
                                var priceA = parseFloat(a.getAttribute('data-price'));
                                var priceB = parseFloat(b.getAttribute('data-price'));
                                var categoryA = a.getAttribute('data-category').toLowerCase();
                                var categoryB = b.getAttribute('data-category').toLowerCase();

                                switch (filter) {
                                    case 'a-z':
                                        return nameA.localeCompare(nameB);
                                    case 'z-a':
                                        return nameB.localeCompare(nameA);
                                    case 'stock-high-low':
                                        return stockB - stockA;
                                    case 'stock-low-high':
                                        return stockA - stockB;
                                    case 'price-low-high':
                                        return priceA - priceB;
                                    case 'price-high-low':
                                        return priceB - priceA;
                                    case 'category':
                                        return categoryA.localeCompare(categoryB);
                                    default:
                                        return 0;
                                }
                            });

                            // Remover todos los productos y volver a agregarlos ordenados
                            var productList = document.getElementById('productList');
                            productList.innerHTML = '';
                            products.forEach(function(product) {
                                productList.appendChild(product);
                            });
                        });

                        // Función para asegurarse de que la cantidad seleccionada no sobrepase el stock
                        function updateMaxQuantity(input, stock) {
                            if (input.value > stock) {
                                input.value = stock; // Establecer el valor al stock máximo disponible
                            }
                        }

                        function openConfirmationModal2(id) {
                            document.getElementById('confirmationModal2').classList.remove('hidden');
                            // Asigna la acción del botón de confirmación al formulario con el ID proporcionado
                            document.getElementById('confirmButton2').onclick = function() {
                                document.getElementById('deleteP-' + id).submit();
                            };
                        }

                        // Función para cerrar el modal de confirmación
                        function closeConfirmationModal2() {
                            document.getElementById('confirmationModal2').classList.add('hidden'); // Oculta el modal
                        }
                    </script>

                    <style>
                        .filtro{
                            width: 225px;
                        }

                        .barra-bus{
                            margin-left: 10px;
                        }

                        .search-btn{
                            margin:0;
                            padding0;
                            margin-left: -5px;
                            background-color: #FFD79F;
                        }

                        .agregar input, select, textarea{
                            color: black !important; 
                        }

                        .productos{
                            background-color: white;
                        }

                        .productos h3{
                            color: black;
                            font-size: 25px;
                            font-weight: bold;
                            text-align: center;
                        }
                        .card-product{
                            background-color: #EAEAEA;
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
                        }

                        .product-name{
                            color: black;
                        }

                        .product-description{
                            color: black;
                            font-size: 15px; 
                        }

                        .uni-cat{
                            color: black; 
                            font-size: 13px;
                            margin-top: -8px
                        }

                        .product-price{
                            color: black;
                            font-size: 25px;
                            font-weight: bold;
                            position: absolute;
                            right: 15px;
                            margin-top: -35px;
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
                        
                        #confirmationModal {
                            
                            z-index: 9999; /* Asegura que esté en el frente de otros elementos */
                            
                        }

                        #confirmationModal2 {
                            
                            z-index: 9999; /* Asegura que esté en el frente de otros elementos */
                            
                        }

                        .modal{
                            color: black !important;
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