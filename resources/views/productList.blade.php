<head>
<title>Productos</title>
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
                    <h3 class="text-lg font-semibold mb-4">Lista de Productos</h3>
                    <!-- Mensaje de éxito -->
                    @if(session('success'))
                        <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
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
                            
                            <!-- Formulario para agregar al carrito -->
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <div class="mt-4 div-cantidad">
                                    <div class="div-cant">
                                        <label for="quantity_{{ $product->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200 cantidad">Cantidad:</label>
                                        <input type="number" name="quantity" id="quantity_{{ $product->id }}" class="mt-1 block w-full border rounded p-1 text-black input-cantidad" min="1" max="{{ $product->stock }}" value="1" onchange="updateMaxQuantity(this, {{ $product->stock }})" required @if($product->stock === 0) disabled @endif>
                                    </div>
                                    
                                    <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 btn-carrito" @if($product->stock === 0) disabled @endif>
                                        <img src="{{ asset('images/carrito.png') }}" alt="carrito" height="27px" width="27px" class="img-carrito">
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endforeach
                    </div>  

                    <script>
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
                        
                        .div-cantidad{
                            margin: 0;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        }

                        .div-cant{
                            display: flex;
                            align-items: center;
                        }
                        
                        .input-cantidad{
                            width: 70px;
                            margin-left: 5px;
                        }

                        .btn-carrito{
                            width: 70px;
                            height: 35px;
                            margin: 0;
                            margin-top: 3px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            background-color: #6CD457;
                        }

                        .cantidad{
                            color:black;
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