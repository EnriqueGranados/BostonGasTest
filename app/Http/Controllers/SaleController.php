<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailsSales;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        // Validación de los campos requeridos
        $request->validate([
            'customer' => 'required|string|max:255',
            'payment' => 'required|string',
            'dui' => 'required|string|max:255',
            'card_number' => 'nullable|string|max:255',
            'transfer_number' => 'nullable|string|max:255',
        ]);

        // Crear un array para los detalles de la venta
        $detailsSale = [
            'nombre_comprador' => $request->customer,
            'dui_cliente' => $request->dui,
        ];

        // Añadir detalles del método de pago según el caso
        if ($request->payment === 'Tarjeta' && !empty($request->card_number)) {
            $detailsSale['numero_tarjeta'] = $request->card_number;
        } elseif ($request->payment === 'Transferencia' && !empty($request->transfer_number)) {
            $detailsSale['numero_transferencia'] = $request->transfer_number;
        }

        // Convertir el array a formato JSON
        $detailsSaleJson = json_encode($detailsSale);

        // Calcular el total de la venta
        $total = 0;
        $cart = session('cart', []); // Obtener los productos del carrito de la sesión
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Crear el registro en la base de datos
        $sale = Sale::create([
            'seller' => Auth::user()->name . ' ' . Auth::user()->last_name, // Concatenar nombre y apellido
            'customer' => $request->customer,
            'dui' => $request->dui,
            'payment' => $request->payment,
            'details_sale' => $detailsSaleJson,
            'total' => $total, // Agregar el total
        ]);

        // Obtener el último id de la venta
        $saleId = $sale->id;

        // Recorrer los productos en el carrito y agregarlos a la tabla details_sales
        $cart = session('cart', []); // Obtener los productos del carrito de la sesión
        foreach ($cart as $productId => $item) {
            DetailsSales::create([
                'account_identifier' => $saleId,
                'nombre' => $item['name'],
                'stock' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['quantity'],
            ]);

            // Actualizar el stock del producto en la tabla products
            $product = Product::find($productId);
            if ($product) {
                $product->stock -= $item['quantity']; // Restar la cantidad comprada
                $product->save();
            }
        }

        // Limpiar el carrito de la sesión
        session()->forget('cart');

        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'Venta registrada con éxito. El resumen de compra ha sido limpiado.');
    }
}
