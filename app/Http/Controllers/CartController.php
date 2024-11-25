<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        $product = Product::find($id);

        // Verifica si el producto existe
        if (!$product) {
            return redirect()->back()->with('error', 'Producto no encontrado.');
        }

        // Validar la cantidad ingresada
        $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1', // Mínimo de 1
                'max:' . $product->stock, // Máximo según el stock del producto
            ],
        ], [
            'quantity.min' => 'La cantidad debe ser al menos 1.',
            'quantity.max' => 'No hay suficiente stock disponible para este producto.',
        ]);

        // Asegúrate de que la cantidad no supere el stock
        $quantity = $request->input('quantity');
        
        // Guarda el producto en la sesión del carrito
        $cart = Session::get('cart', []);
        $cart[$id] = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $quantity,
            'image' => $product->image
        ];

        Session::put('cart', $cart);
        return redirect()->back()->with('success', 'Producto agregado al carrito.');
    }

    public function showCart()
    {
        $cart = Session::get('cart', []);

        // Obtener el usuario autenticado desde la base de datos
        $user = User::find(Auth::id());

        return view('cart', compact('cart','user'));
    }

    public function checkout()
    {
        $cart = Session::get('cart', []);
        
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $product->stock -= $item['quantity'];
                $product->save();
            }
        }
        // Limpiar el carrito
        Session::forget('cart');
    
        // Redirigir con mensaje de éxito
        return redirect()->route('cart.show')->with('success', 'Venta realizada con éxito.');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Producto eliminado del carrito.');
    }
    

}
