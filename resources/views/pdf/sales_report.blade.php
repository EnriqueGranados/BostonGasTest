<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Ventas - {{ $tipo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Reporte de Ventas - {{ ucfirst($tipo) }}</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Método de Pago</th>
                <th>Fecha</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->customer }}</td>
                    <td>{{ $sale->payment }}</td>
                    <td>{{ $sale->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>${{ number_format($sale->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Total de Ventas: ${{ number_format($totalSales, 2) }}</h2>
</body>
</html>
