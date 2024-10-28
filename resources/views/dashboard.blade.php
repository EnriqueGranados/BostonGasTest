<head>
<title>Dashboard</title>
</head>
<x-app-layout>
    
    <div class="py-12" style="padding-top: 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Meta Diaria de Ventas -->
                    <div class="my-4 meta">
                    <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Meta Diaria de Ventas</h6>
                        <div class="relative pt-1">
                            <div class="flex mb-2 items-center justify-between">
                                <div>
                                    <span class="font-semibold">Ventas de Hoy:</span> $<span id="currentSales">{{ number_format($salesToday, 2) }}</span>
                                </div>
                                <div>
                                <span class="font-semibold">Meta: </span><span>$250.00</span>
                                </div>
                                
                            </div>
                            <div class="flex h-2 mb-2" style="z-index: 9999;">
                                <div class="w-full bg-gray-200">
                                    <div id="progressBar" class="bg-teal-500 h-2" style="width: {{ ($salesToday / 250) * 100 }}% !important; background-color: #38b2ac;">
                                    </div>
                                </div>
                            </div>

                            <div id="salesGoalMessage" class="text-sm font-semibold">
                                @if ($salesToday >= 250)
                                    ¡Meta diaria alcanzada!
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr style="margin-bottom: 40px; margin-top: 40px;" class="border-t-2 border-gray-300 dark:border-gray-600 my-4">
                    <!-- Últimas 5 Ventas -->
                    <div class="my-4" style="margin-top: 30px;">
                    <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Últimas 5 Ventas</h6>
                        <table class="min-w-full bg-white" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">
                            <thead class="the">
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider" style="width: 5%; ">ID</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider" style="width: 25%; ">Empleado</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider" style="width: 25%; ">Cliente</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider" style="width: 18%; ">Método de Pago</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider" style="width: 7%; ">Total</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider" style="width: 20%; ">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="tbo">
                                @foreach ($latestSales as $sale)
                                    <tr>
                                        <td class="px-6 py-4 border-b border-gray-300 text-center">{{ $sale->id }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300">{{ $sale->seller }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300">{{ $sale->customer }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300">{{ $sale->payment }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300">${{ number_format($sale->total, 2) }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300">{{ $sale->created_at->format('d-m-Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr style="margin-bottom: 40px; margin-top: 40px;" class="border-t-2 border-gray-300 dark:border-gray-600 my-4">
                    <!-- Productos con Menor Stock -->
                    <div class="my-4">
                    <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Productos con Menos Stock</h6>
                        <table class="min-w-full bg-white" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">
                            <thead class="the">
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider" style="width: 5%; ">ID</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Producto</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Descripcion</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Categoría</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider" style="width: 10%; ">Stock</th>
                                </tr>
                            </thead>
                            <tbody class="tbo">
                                @foreach ($lowStockProducts as $product)
                                    <tr>
                                        <td class="px-6 py-4 border-b border-gray-300 text-center">{{ $product->id }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300">{{ $product->name }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300">{{ $product->description }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300">{{ $product->category }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300 text-center">
                                            <span class="@if($product->stock > 100) text-green-600 @elseif($product->stock > 50) text-orange-500 @else text-red-600 @endif">
                                                {{ $product->stock }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <style>
        .dashboard{
            background-color: white;
        }

        .dashboard h3{
            color: black;
            font-size: 25px;
            font-weight: bold;
            text-align: center;
        }

        .meta{
            background-color: #EAEAEA;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            padding: 20px;
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

        #progressBar {
        background-color: #38b2ac !important;
        height: 8px !important;
        width: 50% !important; /* Ajustar este valor para ver si cambia */
    }
    </style>
</x-app-layout>
<body>
<footer style="width: 100%; height: 40px; display: flex; justify-content: center; align-items: center; background-color: white; overflow: hidden;">
    <p style="color: gray;">© 2024 - Boston Gas, todos los derechos reservados</p>
</footer> 
</body>