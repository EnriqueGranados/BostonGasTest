<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Función para mostrar la vista de edición de productos
    public function editProduct(Request $request)
    {
        // Obtener el término de búsqueda desde el parámetro 'search'
        $searchTerm = $request->input('search');

        // Consultar productos y filtrar si existe un término de búsqueda
        $products = Product::query();

        if ($searchTerm) {
            $products->where('name', 'LIKE', "%{$searchTerm}%");
        }

        $categories = Category::all(); // Obtener todas las categorías

        // Retorna la vista 'editProduct' y pasa los productos filtrados
        return view('editProduct', [
            'products' => $products->get(),
            'categories' => $categories,
        ]);
    }

    // Función para almacenar un nuevo producto
    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255', // El nombre es obligatorio y debe ser una cadena con un máximo de 255 caracteres
            'description' => 'nullable|string', // La descripción es opcional y debe ser una cadena
            'price' => 'required|numeric', // El precio es obligatorio y debe ser un número
            'stock' => 'required|integer', // El stock es obligatorio y debe ser un entero
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // La imagen es opcional, pero si se proporciona, debe ser una imagen válida
            'category' => 'required|string|max:255', // La categoría es obligatoria y debe ser una cadena con un máximo de 255 caracteres
        ]);

        // Inicializa la variable para el nombre de la imagen
        $imageName = null;

        // Verifica si se ha subido una imagen
        if ($request->hasFile('image')) {
            // Genera un nombre único para la imagen basado en la hora actual
            $imageName = time() . '.' . $request->image->extension();
            // Mueve la imagen al directorio 'images'
            $request->image->move(public_path('images'), $imageName);
        }

        // Buscar el nombre de la categoría seleccionada
        $category = DB::table('categories')->where('id', $request->category)->value('name');

        // Crea un nuevo producto en la base de datos
        Product::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'image' => $imageName, // Almacena el nombre de la imagen
            'category' => $category // Almacena el ID de la categoría
        ]);

        // Redirige a la vista de edición de productos con un mensaje de éxito
        return redirect()->route('editProduct')->with('success', 'Producto agregado exitosamente.');
    }

    // Función para eliminar un producto
    public function deleteProduct($id)
    {
        // Busca el producto por ID
        $product = Product::find($id);
        
        // Verifica si el producto existe
        if ($product) {
            $product->delete(); // Elimina el producto de la base de datos
            return redirect()->back()->with('success', 'Producto eliminado correctamente.');
        }
        
        // Si el producto no se encuentra, redirige con un mensaje de error
        return redirect()->back()->with('error', 'Producto no encontrado.');
    }

    // Función para mostrar la vista de edición de un producto específico
    public function editOneProduct($id)
    {
        // Busca el producto por ID, o falla si no se encuentra
        $product = Product::findOrFail($id);

        $categories = Category::all(); // Obtiene todas las categorías

        // Retorna la vista 'editOneProduct' y pasa el producto encontrado
        return view('editOneProduct', compact('product', 'categories'));
    }

    // Función para actualizar un producto existente
    public function update(Request $request, $id)
    {
        // Validación de los datos del formulario para la actualización
        $request->validate([
            'name' => 'required|string|max:255', // El nombre es obligatorio
            'description' => 'nullable|string|max:1000', // La descripción es opcional con un límite de 1000 caracteres
            'price' => 'required|numeric', // El precio es obligatorio
            'stock' => 'required|integer', // El stock es obligatorio
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // La imagen es opcional, con validaciones
            'category' => 'required|exists:categories,id', // La categoría es obligatoria
        ]);

        // Busca el producto por ID, o falla si no se encuentra
        $product = Product::findOrFail($id);

        // Verifica si se ha subido una nueva imagen
        if ($request->hasFile('image')) {
            // Genera un nuevo nombre para la imagen
            $imageName = time() . '.' . $request->image->extension();
            // Mueve la nueva imagen al directorio 'images'
            $request->image->move(public_path('images'), $imageName);
            // Actualiza el nombre de la imagen en el producto
            $product->image = $imageName;
        }
        // Buscar el nombre de la categoría seleccionada
        $category = DB::table('categories')->where('id', $request->category)->value('name');
        if (!$category) {
            return back()->withErrors(['category' => 'Categoría no válida.']);
        }
        
        // Actualiza los campos del producto
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->category = $category;
        // Guarda los cambios en la base de datos
        $product->save();

        // Almacenar mensaje de éxito en la sesión
        session()->flash('success', 'Producto actualizado con éxito.');
        
        // Redirigir de vuelta a la vista de edición
        return redirect()->route('editProduct', ['id' => $product->id]);
    }
}
