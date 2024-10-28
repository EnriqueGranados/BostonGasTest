@if ($user->role === 'admin')
    <head>
    <title>Administrar Ventas</title>
    </head>
@endif

@if ($user->role === 'employee')
    <head>
    <title>Visualizar Ventas</title>
    </head>
@endif
<x-app-layout>
    

    
    @if ($user->role === 'admin')
    <div class="ventas">
        <h3 class="text-lg font-semibold mb-4">Administrar Ventas</h3>
    </div>
    @endif
    @if ($user->role === 'employee')
    <div class="ventas">
        <h3 class="text-lg font-semibold mb-4">Visualizar Ventas</h3>
    </div>
    @endif

    

    <div class="py-12" style="margin-top: 0; padding-top:0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" style="background-color: white; padding-top:0;">
                @if (session('success'))
                    <div class="bg-green-500 text-white p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Listado de Ventas</h6>

                    <!-- Tabla para mostrar todas las ventas -->
                    <div class="mt-6" style="margin-top: 8px;">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">
                            <thead class="bg-gray-50 dark:bg-gray-700 the">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400 tracking-wider" style="width: 4%; height: 20px;">
                                        Id
                                    </th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400 tracking-wider" style="width: 23%; height: 20px;">
                                        Vendedor
                                    </th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400 tracking-wider" style="width: 23%; height: 20px;">
                                        Cliente
                                    </th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400 tracking-wider" style="width: 20%; height: 20px;">
                                        Método de Pago
                                    </th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400 tracking-wider" style="width: 16%; height: 20px;">
                                        Monto de la Venta
                                    </th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400 tracking-wider" style="width: 14%; height: 20px;">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="tbo bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach($sales as $sale)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 text-center">{{ $sale->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-200">{{ $sale->seller }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-200">{{ $sale->customer }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-200">{{ $sale->payment }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-200">${{ $sale->total }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-200">
                                        @if ($user->role === 'employee')
                                            <div style="display: flex; justify-content: center;">
                                                <a href="{{ route('sales.generatePDF', $sale->id) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">
                                                    <img src="{{ asset('images/pdf.png') }}" alt="factura" width="30px">
                                                </a>
                                            </div>
                                        @endif    
                                        <!-- Botón Mostrar -->
                                            <div class="btns">
                                                
                                                @if ($user->role === 'admin')
                                                <a href="{{ route('sales.generatePDF', $sale->id) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">
                                                    <img src="{{ asset('images/pdf.png') }}" alt="factura" width="30px">
                                                </a>
                                                <!-- Botón Eliminar visible solo para admin -->
                                                
                                                    <form id="delVen-{{ $sale->id }}" action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" onclick="openConfirmationModal2({{ $sale->id }})" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                            <img src="{{ asset('images/borrar.png') }}" alt="borrar" width="30px">
                                                        </button>
                                                    </form>

                                                    <!-- Modal de confirmación para eliminar venta -->
                                                    <div id="confirmationModal2" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
                                                        <div class="flex items-center justify-center h-full">
                                                            <div class="bg-white p-5 rounded shadow-lg modal">
                                                                <h3 class="font-bold text-lg" style="left: 0; width: 100px; font-size: 25px;">Confirmación</h3>
                                                                <p>¿Estás seguro que deseas eliminar esta venta?</p>
                                                                <div class="mt-4 flex justify-end">
                                                                    <button onclick="closeConfirmationModal2()" class="btn-c bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancelar</button>
                                                                    <button id="deleteButton" onclick="confirmDelete()" class="btn-save bg-indigo-600 text-white px-4 py-2 rounded-md ml-2">Confirmar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Si no hay ventas -->
                        @if($sales->isEmpty())
                            <div class="mt-6 text-center text-gray-500 dark:text-gray-200">
                                No hay ventas registradas.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Variable global para almacenar el ID de la venta a eliminar
        let ventaIdToDelete = null;

        // Abre el modal y guarda el ID de la venta
        function openConfirmationModal2(id) {
            ventaIdToDelete = id;
            document.getElementById('confirmationModal2').classList.remove('hidden');
        }

        // Cierra el modal
        function closeConfirmationModal2() {
            document.getElementById('confirmationModal2').classList.add('hidden');
        }

        // Confirma la eliminación y envía el formulario
        function confirmDelete() {
            if (ventaIdToDelete !== null) {
                document.getElementById(`delVen-${ventaIdToDelete}`).submit();
            }
            closeConfirmationModal2();
        }
    </script>
    <style>
        .ventas{
            background-color: white;
            margin-top: 24px;
        }

        .ventas h3{
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

        .btns a{
            padding-left: 10px;
        }

        .btns button{
            padding-right: 10px;
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
</x-app-layout>
<footer style="width: 100%; height: 40px; display: flex; justify-content: center; align-items: center; background-color: white; overflow: hidden;">
    <p style="color: gray;">© 2024 - Boston Gas, todos los derechos reservados</p>
</footer> 
</body>