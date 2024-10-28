<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all(); // Muestra todas las categorías
        return view('category', compact('categories')); // Cambiamos a la vista 'category'
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría creada con éxito.');
    }

    public function destroy($id)
{
    // Encontrar la categoría por su ID y eliminarla
    $category = Category::findOrFail($id);
    $category->delete();

    // Redirigir con un mensaje de éxito
    return redirect()->route('categories.index')->with('success', 'Categoría eliminada correctamente.');
}
}
