<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AllProductController extends Controller
{
    public function index(Request $request)
    {
        // Construir la consulta base
        $query = Product::query();

        // Aplicar filtro de búsqueda si se proporciona un término
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Obtener los productos filtrados o todos los productos
        $products = $query->get();

        // Obtener el usuario autenticado desde la base de datos
        $user = User::find(Auth::id());

        return view('productList', compact('products', 'user'));
    }
}
