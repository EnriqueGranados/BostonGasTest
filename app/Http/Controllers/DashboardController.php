<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
       
        // Obtener las ventas del día actual
        $salesToday = Sale::whereDate('created_at', today())->sum('total');

        // Obtener las últimas 5 ventas
        $latestSales = Sale::orderBy('created_at', 'desc')->take(5)->get();

        // Obtener los 5 productos con menos stock
        $lowStockProducts = Product::orderBy('stock', 'asc')->take(5)->get();

        // Obtener el usuario autenticado desde la base de datos
        $user = User::find(Auth::id());

        return view('dashboard', compact('salesToday','latestSales','lowStockProducts','user'));
    }
}
