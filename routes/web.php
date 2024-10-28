<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AllProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\DashboardController;



use Illuminate\Support\Facades\Route;

// Ruta principal que retorna la vista de bienvenida
Route::get('/', function () {
    return view('welcome');
})->name('welcome');
// Ruta para el Dashboard, protegida por autenticación y verificación
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Agrupación de rutas de Productos, protegidas por autenticación y verificación
Route::middleware(['auth', 'verified'])->group(function () {
    // Ruta para editar productos
    Route::get('/edit-product', [ProductController::class, 'editProduct'])->name('editProduct');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    
    // Ruta para almacenar un nuevo producto
    Route::post('/store-product', [ProductController::class, 'store'])->name('storeProduct');
    
    // Ruta para editar un producto específico
    Route::get('/edit-product/{id}', [ProductController::class, 'editOneProduct'])->name('editOneProduct');
    
    // Ruta para actualizar un producto específico
    Route::put('/update-product/{id}', [ProductController::class, 'update'])->name('updateProduct');
    
    // Ruta para eliminar un producto específico
    Route::delete('/products/{id}', [ProductController::class, 'deleteProduct'])->name('deleteProduct');

    Route::get('/productos', [AllProductController::class, 'index'])->name('products.index');

    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');

    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');

    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');

    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');

    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');

    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');

    Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

    Route::resource('categories', CategoryController::class);

    // Ruta para mostrar todas las ventas
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');

    // Ruta para eliminar una venta
    Route::delete('/sales/{id}', [SalesController::class, 'destroy'])->name('sales.destroy');

    Route::get('/sales/{id}/pdf', [SalesController::class, 'generatePDF'])->name('sales.pdf');

    Route::get('/sales/generate-pdf', [SalesController::class, 'generatePDF'])->name('sales.generatePDF');

    Route::get('/sales/generate-pdf/{id}', [SalesController::class, 'generatePDF'])->name('sales.generatePDF');
    Route::get('/reportes', [SalesReportController::class, 'index'])->name('reporte.index');
    Route::post('/reportes/generate', [SalesReportController::class, 'generateReport'])->name('reportes.generate');
    Route::get('/reportes/pdf/{tipo}', [SalesReportController::class, 'showPdf'])->name('reportes.pdf');



});

// Agrupación de rutas de perfil, protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Ruta para editar el perfil del usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    
    // Ruta para actualizar el perfil del usuario
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Ruta para eliminar el perfil del usuario
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Inclusión de las rutas de autenticación generadas por Laravel
require __DIR__.'/auth.php';
