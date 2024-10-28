<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de Venta</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Factura de Venta #{{ $sale->id }}</h1>
    
    <p><strong>Gasolinera Boston Gas</strong></p>
    <p><strong>Calle Principal, Yucuaiquín, La Unión</strong></p>
    <p><strong>Teléfono:</strong> 2663-2780</p>
    <p><strong>Email:</strong> bostongas_station@gmail.com</p>
    <hr>
    <p><strong>Vendedor:</strong> {{ $sale->seller }}</p>
    <p><strong>Cliente:</strong> {{ $sale->customer }}</p>
    <p><strong>Método de Pago:</strong> {{ $sale->payment }}</p>
    <hr>
    <h3>Detalles de la Venta</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $detail)
                <tr>
                    <td>{{ $detail->nombre }}</td>
                    <td>{{ $detail->stock }}</td>
                    <td>${{ $detail->price }}</td>
                    <td>${{ $detail->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total de la Venta:</strong> ${{ $details->sum('total') }}</p>
</body>
</html>
