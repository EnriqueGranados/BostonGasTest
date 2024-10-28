<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AllProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::all();

        // Obtener el usuario autenticado desde la base de datos
        $user = User::find(Auth::id());

        return view('productList',compact('products','user'));
    }
}
