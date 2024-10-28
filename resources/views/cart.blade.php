<head>
<title>Carrito</title>
</head>
<x-app-layout>
    <div class="py-12" style="background-color: white; padding: 0; margin-top: 25px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" style="background-color: white; padding: 0; margin: 0;">
                <div class="p-6 text-gray-900 dark:text-gray-100 carr" style="background-color: white; padding: 0; margin: 0;">
                
                    <h3 class="text-lg font-semibold mb-4">Resumen de la Venta</h3>
    
                    @if(Session::has('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    @if (empty($cart))
                        <div class="p-6 rounded" style="background-color: #EAEAEA; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); padding: 20px; margin: 5px;">
                            <h6 class="font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">El carrito se encuentra vacío.</h6>
                        </div>
                    @else
                        @php
                            $total = 0;
                        @endphp
                        <table class="table-auto w-full text-left mb-4" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">
                            <thead class="the">
                                <tr>
                                    <th class="px-4 py-2" style="width: 23%;">Nombre del Producto</th>
                                    <th class="px-4 py-2" style="width: 15%;">Cantidad</th>
                                    <th class="px-4 py-2" style="width: 20%;">Precio Unitario</th>
                                    <th class="px-4 py-2" style="width: 20%;">Subtotal</th>
                                    <th class="px-4 py-2" style="width: 8%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="tbo">
                                @foreach ($cart as $id => $item)
                                    @php
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $total += $subtotal;
                                    @endphp
                                    <tr>
                                        <td class="border px-4 py-2">{{ $item['name'] }}</td>
                                        <td class="border px-4 py-2 text-center">{{ $item['quantity'] }}</td>
                                        <td class="border px-4 py-2 text-center">${{ number_format($item['price'], 2) }}</td>
                                        <td class="border px-4 py-2 text-center">${{ number_format($subtotal, 2) }}</td>
                                        <td class="border px-4 py-2 text-center">
                                            <!-- Botón de eliminar -->
                                            <form id="delPro-{{ $id }}" action="{{ route('cart.remove', $id) }}" method="POST">
                                                @csrf
                                                <button type="button" onclick="openConfirmationModal2({{ $id }})">
                                                    <img src="{{ asset('images/borrar.png') }}" alt="eliminar" width="30px">
                                                </button>
                                            </form>

                                            <!-- Modal de confirmación para eliminar producto -->
                                            <div id="confirmationModal2" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
                                                <div class="flex items-center justify-center h-full">
                                                    <div class="bg-white p-5 rounded shadow-lg modal">
                                                        <h3 class="font-bold text-lg" style="left: 0; width: 100px; font-size: 25px;">Confirmación</h3>
                                                        <p>¿Estás seguro de que deseas eliminar este producto del carrito?</p>
                                                        <div class="mt-4 flex justify-end">
                                                            <button onclick="closeConfirmationModal2()" class="btn-c bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancelar</button>
                                                            <button id="deleteButton" onclick="confirmDelete()" class="btn-save bg-indigo-600 text-white px-4 py-2 rounded-md ml-2">Confirmar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- Fila para el total de la venta -->
                                <tr>
                                    <td colspan="3" class="border px-4 py-2 font-bold text-center">Total de la Venta</td>
                                    <td colspan="2" class="border px-4 py-2 text-center font-bold" style="padding-right:120px;">${{ number_format($total, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <hr style="margin-bottom: 30px; margin-top: 30px;" class="border-t-2 border-gray-300 dark:border-gray-600 my-4">
                        <form id="carritoForm" method="POST" action="{{ route('sales.store') }}" class="mb-4">
                            @csrf
                            

                            <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Datos del Cliente</h6>
                            <div class="name-dui">
                                <div class="mb-4">
                                    
                                    <input type="text" placeholder="Nombre del Cliente" id="cliente" name="customer" class="mt-1 block w-full p-2 border border-gray-300 rounded-md text-black" required>
                                </div>

                                <div id="duiField" class="mb-4">
                                    
                                    <input type="text" placeholder="DUI del Cliente" id="dui" name="dui" class="mover mt-1 block w-full p-2 border border-gray-300 rounded-md text-black" required>
                                </div>
                            </div>
                            

                            <div class="mb-4">
                            <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Método de Pago</h6>
                                <select id="metodo_pago" name="payment" class="sel mt-1 block w-full p-2 border border-gray-300 rounded-md text-black" required onchange="showPaymentFields()">
                                    <option value="">Seleccionar método de pago</option>
                                    <option value="Tarjeta">Tarjeta</option>
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Efectivo">Efectivo</option>
                                </select>
                            </div>

                            <div id="additionalFields" class="hidden">
                                <div id="cardField" class="mb-4 hidden" style="margin: 0px;">
                                    <input type="text" id="card_number" placeholder="Número de Tarjeta" name="card_number" class="sel mt-1 block w-full p-2 border border-gray-300 rounded-md text-black">
                                </div>
                                <div id="transferField" class="mb-4 hidden name-dui" style="margin-bottom: 10px;">
                                    
                                    <input type="text" placeholder="Nombre del Banco" id="bank_name" name="bank_name" class="hidden mt-1 block w-full p-2 border border-gray-300 rounded-md text-black">
                                    
                                    <input type="text" placeholder="Número de Cuenta" id="account_number" name="transfer_number" class="mover hidden mt-1 block w-full p-2 border border-gray-300 rounded-md text-black">
                                </div>
                            </div>  
                            
                            <hr style="margin-bottom: 30px; margin-top: 30px;" class="border-t-2 border-gray-300 dark:border-gray-600 my-4">
                            <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Vendedor</h6>
                            <div class="mb-4 name-dui" style="padding: 0px; margin: 0px;">
                                
                                <input type="text" id="vendedor" name="vendedor" value="{{ auth()->user()->name . ' ' . auth()->user()->last_name }}" class="sel mt-1 block w-full p-2 border border-gray-300 rounded-md text-black"  style="height: 42px;"  readonly>
                            
                                <button type="button" onclick="validateAndOpenModal()" class="btn-save mt-4 bg-blue-500 text-white p-2 rounded mover" style="margin: 0px; width: 150px; height: 43px; margin-top: 4px; margin-left: 20px;">Realizar Venta</button>
                            </div>
                            
                        </form>

                        <!-- Modal de confirmación para guardar empleado -->
                        <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
                            <div class="flex items-center justify-center h-full">
                                <div class="bg-white p-5 rounded shadow-lg modal">
                                    <h3 class="font-bold text-lg" style="left: 0; width: 100px; font-size: 25px;">Confirmación</h3>
                                    <p>¿Estás seguro de que deseas realizar esta venta?</p>
                                    <div class="mt-4 flex justify-end">
                                        <button onclick="closeConfirmationModal()" class="btn-c bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancelar</button>
                                        <button id="confirmButton" onclick="document.getElementById('carritoForm').submit();" class="btn-save bg-indigo-600 text-white px-4 py-2 rounded-md ml-2">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <style>
        .carr{
            background-color: white;
            margin-top: 24px;
        }

        .carr h3{
            color: black;
            font-size: 25px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 25px;
        }

        .name-dui{
            display: flex;
        }

        .name-dui input{
            width: 350px;
        }

        .sel{
            width: 350px;
        }

        .mover{
            margin-left: 20px;
        }

        .btn-save{
            background-color: #2A4B89;
        }

        .btn-save:hover {
            background-color: #476192; 
        }

        .btn-c:hover {
            background-color: #a2a2a2; 
        }

        .modal{
            color: black !important;
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
    </style>

    <script>
        function showPaymentFields() {
            const paymentMethod = document.getElementById('metodo_pago').value;
            const additionalFields = document.getElementById('additionalFields');
            const cardField = document.getElementById('cardField');
            const transferField = document.getElementById('transferField');
            const duiField = document.getElementById('duiField');

            const banco = document.getElementById('bank_name');
            const cuenta = document.getElementById('account_number');

            additionalFields.classList.remove('hidden');
            cardField.classList.add('hidden');
            transferField.classList.add('hidden');

            if (paymentMethod === 'Tarjeta') {
                cardField.classList.remove('hidden');
                banco.classList.add('hidden');
                cuenta.classList.add('hidden');
                transferField.classList.add('hidden');

            } else if (paymentMethod === 'Transferencia') {
                transferField.classList.remove('hidden');
                banco.classList.remove('hidden');
                cuenta.classList.remove('hidden');
            }
            else{
                banco.classList.add('hidden');
                cuenta.classList.add('hidden');
                cardField.classList.add('hidden');
                transferField.classList.add('hidden');
                additionalFields.classList.add('hidden');
            }

            duiField.classList.remove('hidden');
        }

        // Función para validar campos y abrir el modal
        function validateAndOpenModal() {
                // Selecciona el formulario
                const form = document.getElementById('carritoForm');

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

            
            // Variable global para almacenar el ID del producto a eliminar
            let productIdToDelete = null;

            // Abre el modal y guarda el ID del producto
            function openConfirmationModal2(id) {
                productIdToDelete = id;
                document.getElementById('confirmationModal2').classList.remove('hidden');
            }

            // Cierra el modal
            function closeConfirmationModal2() {
                document.getElementById('confirmationModal2').classList.add('hidden');
            }

            // Confirma la eliminación y envía el formulario
            function confirmDelete() {
                if (productIdToDelete !== null) {
                    document.getElementById(`delPro-${productIdToDelete}`).submit();
                }
                closeConfirmationModal2();
            }

    </script>
</x-app-layout>
<body>
<footer style="width: 100%; height: 40px; display: flex; justify-content: center; align-items: center; background-color: white; overflow: hidden;">
    <p style="color: gray;">© 2024 - Boston Gas, todos los derechos reservados</p>
</footer> 
</body>