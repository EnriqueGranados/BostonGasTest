<head>
<title>Reporte de Ventas</title>
</head>
<body>
<x-app-layout>
    <div class="reporte">
        <h3 class="text-lg font-semibold mb-4">Reporte de Ventas</h3>
    </div>
    
    <div class="py-12" style="padding: 0; margin:0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="padding-top: 0; margin-top:0;">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" style="padding-top: 0; margin-top:0; background-color: white;">
                <div class="p-6 text-gray-900 dark:text-gray-100" style="padding-top: 0; margin-top:0;">
                        <form action="{{ route('reportes.generate') }}" method="POST">
                            @csrf
                            <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Tipo de Reporte</h6>
                            
                            <div class="mover">
                                <select name="tipo" id="tipo" class="block w-full rounded-md mb-4">
                                    <option value="diario">Diario</option>
                                    <option value="semanal">Semanal</option>
                                    <option value="mensual">Mensual</option>
                                    <option value="anual">Anual</option>
                                </select>
                                <button type="submit" class="btn-re mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">Generar Reporte</button>
                            </div>
                            
                        </form>

                        @if(session('success'))
                            <div class="mt-4 text-green-600">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mt-4 text-red-600">
                                {{ session('error') }}
                            </div>
                        @endif
                        <hr style="margin-bottom: 30px; margin-top: 30px;" class="border-t-2 border-gray-300 dark:border-gray-600 my-4">
                        <!-- Mostrar datos del reporte -->
                        @if(isset($sales) && count($sales) > 0)
                        <h6 class=" font-semibold text-gray-800 dark:text-gray-200 mb-4" style="color: black; margin-bottom: 3px;">Reporte</h6>
                            <table class="min-w-full bg-white border border-gray-300" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">
                                <thead class="the">
                                    <tr class="bg-gray-200">
                                        <th class="py-2 px-4 border-b" style="width: 5%; height: 50px;">ID</th>
                                        <th class="py-2 px-4 border-b" style="width: 25%; height: 50px;">Cliente</th>
                                        <th class="py-2 px-4 border-b" style="width: 20%; height: 50px;">Método de Pago</th>
                                        <th class="py-2 px-4 border-b" style="width: 30%; height: 50px;">Fecha</th>
                                        <th class="py-2 px-4 border-b" style="width: 20%; height: 50px;">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="tbo">
                                    @foreach($sales as $sale)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ $sale->id }}</td>
                                            <td class="py-2 px-4 border-b">{{ $sale->customer }}</td>
                                            <td class="py-2 px-4 border-b">{{ $sale->payment }}</td>
                                            <td class="py-2 px-4 border-b">{{ $sale->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td class="py-2 px-4 border-b">${{ number_format($sale->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="mt-4" style="color: black; margin-top: 20px; margin-bottom: 40px;">
                                <strong>Total de Ventas: ${{ number_format($totalSales, 2) }}</strong>
                            </div>

                            <!-- Enlace para abrir el PDF en una nueva pestaña -->
                            <div class="mt-4">
                                <a href="{{ route('reportes.pdf', ['tipo' => $tipo]) }}" target="_blank" 
                                    class="btn-pdf text-white py-2 px-4 rounded">
                                    Ver / Descargar Reporte PDF
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        
    </div>
    <style>
            .reporte{
                background-color: white;
                margin-top: 24px;
            }

            .reporte h3{
                color: black;
                font-size: 25px;
                font-weight: bold;
                text-align: center;
                margin-top: 24px;
                margin-bottom: 25px;
            }

            .mover{
                display: flex;
                align-items: center;
                margin-top: 5px;
            }

            .mover select{
                width: 350px;
                margin: 0;
                color: black;
            }

            .mover button{
                margin: 0;
            }

            .btn-re{
                background-color: #2A4B89;
                margin-left: 20px !important;
                height: 43px;
            }

            .btn-re:hover {
                background-color: #476192; 
            }

            .btn-pdf{
                background-color: #2A4B89 !important;
                height: 55px !important;
                padding: 12px;
            }

            .btn-pdf:hover {
                background-color: #476192 !important; 
            }


            .tbo tr{
                background-color: #D9D9D9;
            }
            .tbo td{
                color: black;
                border: solid white 1px;
                height: 30px !important;
                padding: 8px;
                padding-left: 15px;
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
</x-app-layout>
<footer style="width: 100%; height: 40px; display: flex; justify-content: center; align-items: center; background-color: white; overflow: hidden;">
    <p style="color: gray;">© 2024 - Boston Gas, todos los derechos reservados</p>
</footer> 
</body>