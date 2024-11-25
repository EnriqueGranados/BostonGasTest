<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    
    //Test para verificar que el usuario sea redirigido al login si no hay sesion iniciada.
    public function test_usuario_es_redirigido_al_login_si_no_esta_autenticado()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login')); //Debe redirigir a la página de login.
    }

    //Test para verificar que el usuario tenga acceso al dashboard si ha sido autenticado.
    public function test_usuario_tiene_acceso_al_dashboard_si_esta_autenticado()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.
        
        //Verificar que carga correctamente.
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
    }

    //Test para verificar que el usuario al estar en el dashboard es capaz de redirigirse a la sección de productos.
    public function test_usuario_se_redirige_a_la_seccion_de_productos_desde_la_barra_de_navegacion()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.
        
        //Verificar que carga correctamente.
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        //Verificar que el enlace de "Productos" está presente.
        $response->assertSee('Productos');
        $response->assertSee(route('products.index'));

        //Simular el clic en el enlace de "Productos".
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);

        //Verificar que se redirige correctamente a la página de productos
        $response->assertSee('Lista de Productos');
    }

    //Test para verificar que los produtos existentes se cargan correctamente en la sección de productos.
    public function test_productos_existentes_se_cargan_correctamente_en_la_seccion_de_productos()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Crear algunos productos utilizando el ProductFactory.
        $products = Product::factory()->count(5)->create();

        //Acceder a la página de productos.
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);

        //Verificar que cada producto esté presente en la vista con su respectiva información.
        foreach ($products as $product) {
            $response->assertSee($product->name);
            $response->assertSee($product->description);
            $response->assertSee($product->category);
            $response->assertSee((string)$product->price); //Convertir a string.
            $response->assertSee((string)$product->stock); //Convertir a string.
            $response->assertSee(asset('images/' . $product->image)); //Verificar la imagen del producto.
        }
    }

    //Test para verificar que el filtro de búsqueda funciona correctamente.
    public function test_filtro_de_busqueda_funciona_correctamente()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Crear productos con datos variados.
        $products = [
            ['name' => 'Producto A', 'stock' => 50, 'price' => 10, 'category' => 'Electrónica'],
            ['name' => 'Producto B', 'stock' => 20, 'price' => 5, 'category' => 'Hogar'],
            ['name' => 'Producto C', 'stock' => 10, 'price' => 20, 'category' => 'Juguetes'],
            ['name' => 'Producto D', 'stock' => 30, 'price' => 15, 'category' => 'Hogar'],
            ['name' => 'Producto E', 'stock' => 40, 'price' => 25, 'category' => 'Electrónica'],
        ];
        
        foreach ($products as $product) {
            Product::factory()->create($product);
        }

        //Acceder a la página de productos.
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);

        //Simular los filtros y verificar el orden de los productos (Colocamos según deberían aparecer, ya que luego se comparará con el resultado real).
        $filters = [
            'a-z' => ['Producto A', 'Producto B', 'Producto C', 'Producto D', 'Producto E'],
            'z-a' => ['Producto E', 'Producto D', 'Producto C', 'Producto B', 'Producto A'],
            'stock-high-low' => ['Producto A', 'Producto E', 'Producto D', 'Producto B', 'Producto C'],
            'stock-low-high' => ['Producto C', 'Producto B', 'Producto D', 'Producto E', 'Producto A'],
            'price-low-high' => ['Producto B', 'Producto A', 'Producto D', 'Producto C', 'Producto E'],
            'price-high-low' => ['Producto E', 'Producto C', 'Producto D', 'Producto A', 'Producto B'],
            'category' => ['Electrónica', 'Electrónica', 'Hogar', 'Hogar', 'Juguetes'],
        ];

        //Recorremos cada filtro, comparamos con los datos reales y los esperados para ver si coinciden.
        foreach ($filters as $filter => $expectedOrder) {
            //Realizar una solicitud GET simulando el filtro seleccionado.
            $response = $this->get(route('products.index', ['filter' => $filter]));
            $response->assertStatus(200);
    
            //Verificar que los productos estén en el orden esperado.
            foreach ($expectedOrder as $index => $productName) {
                $response->assertSeeInOrder([$productName]);
            }
        }
    }

    //Test para verificar que la barra de búsqueda funciona correctamente.
    public function test_barra_de_busqueda_funciona_correctamente()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Crear productos de prueba.
        $products = [
            ['name' => 'Producto A', 'stock' => 50, 'price' => 10, 'category' => 'Electrónica'],
            ['name' => 'Producto B', 'stock' => 20, 'price' => 5, 'category' => 'Hogar'],
            ['name' => 'Producto C', 'stock' => 10, 'price' => 20, 'category' => 'Juguetes'],
            ['name' => 'Producto D', 'stock' => 30, 'price' => 15, 'category' => 'Hogar'],
            ['name' => 'Producto E', 'stock' => 40, 'price' => 25, 'category' => 'Electrónica'],
        ];

        foreach ($products as $product) {
            Product::factory()->create($product);
        }

        //Acceder a la página de productos.
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);

        //Simular una búsqueda.
        $response->assertSee('Buscar Producto'); //Asegurarse de que la barra de búsqueda esté visible.

        //Buscar un producto específico.
        $searchTerm = 'Producto A';
        $response = $this->get(route('products.index', ['search' => $searchTerm]));

        //Verificar que el producto buscado esté presente en la respuesta.
        $response->assertSee($searchTerm);

        //Verificar que los productos que no coinciden con la búsqueda no estén presentes.
        $response->assertDontSee('Producto B');
        $response->assertDontSee('Producto C');
        $response->assertDontSee('Producto D');
        $response->assertDontSee('Producto E');
    }

    //Test para verificar que no se permite agregar cantidades de productos menores a 1.
    public function test_no_se_permite_el_ingreso_de_cantidades_menores_a_uno_en_el_carrito_de_compras()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Acceder a la página de productos.
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);

        //Crear un producto con stock disponible.
        $product = Product::factory()->create([
            'stock' => 10,
        ]);

        //Simular agregar al carrito con cantidad menor a 1.
        $response = $this->post(route('cart.add', $product->id), [
            'quantity' => 0,
        ]);

        //Asegurar que la respuesta es un error de validación.
        $response->assertSessionHasErrors('quantity');
    }

    //Test para verificar que no se permite agregar más de la cantidad de stock del producto en el carrito de compras.
    public function test_no_se_permite_el_ingreso_de_cantidades_superiores_al_stock_del_producto_en_el_carrito_de_compras()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Acceder a la página de productos.
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);

        //Crear un producto con stock disponible.
        $product = Product::factory()->create([
            'stock' => 5,
        ]);

        //Simular agregar al carrito con cantidad mayor al stock.
        $response = $this->post(route('cart.add', $product->id), [
            'quantity' => 6,
        ]);

        //Asegurar que la respuesta es un error de validación.
        $response->assertSessionHasErrors('quantity');
    }

    //Test para verificar que no se permite agregar al carrito productos agotados.
    public function test_no_se_permite_agregar_al_carrito_de_compras_productos_agotados()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Acceder a la página de productos.
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);

        //Crear un producto sin stock disponible.
        $product = Product::factory()->create([
            'stock' => 0,
        ]);

        //Simular agregar al carrito con cualquier cantidad.
        $response = $this->post(route('cart.add', $product->id), [
            'quantity' => 12,
        ]);

        //Asegurar que la respuesta es un error de validación.
        $response->assertSessionHasErrors('quantity');
    }

    //Test para verificar que se agrega al carrito productos correctamente.
    public function test_agregar_producto_al_carrito_correctamente()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Crear un producto de ejemplo.
        $product = Product::factory()->create([
            'name' => 'Producto Test',
            'price' => 100,
            'stock' => 10,
            'description' => 'Descripción del producto de prueba',
            'category' => 'Categoria Test',
        ]);

        //Acceder a la página de productos.
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);

        //Simular el envío del formulario para agregar el producto al carrito.
        $response = $this->post(route('cart.add', $product->id), [
            'quantity' => 5, 
        ]);

        //Verificar que la redirección haya sido exitosa.
        $response->assertStatus(302); //Redirige después de agregar al carrito.

        //Verificar que un mensaje de éxito esté presente en la sesión.
        $response->assertSessionHas('success', 'Producto agregado al carrito.');
    }

    //Test para verificar que el usuario puede cerrar la sesión desde la vista de productos.
    public function test_usuario_puede_cerrar_sesion_desde_la_vista_de_productos()
    {
        //Crear un usuario autenticado.
        $user = User::factory()->create();
        $this->actingAs($user);

        //Acceder a la página de productos.
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);

        //Verificar que el nombre del usuario aparece en el botón.
        $response->assertSee($user->name);

        //Simular un clic en el botón del usuario (mostrar el dropdown).
        $response = $this->get(route('products.index'), [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest', //Necesario para solicitudes AJAX.
            ]
        ]);

        //Verificar que la opción de "Cerrar Sesión" está presente.
        $response->assertSee('Cerrar Sesión');

        //Simular el clic en "Cerrar Sesión".
        $response = $this->post(route('logout'));

        //Verificar que el usuario fue redirigido a la página de inicio de sesión.
        $response->assertRedirect('/');
        $this->assertGuest(); //Verificar que el usuario ya no está autenticado.
    }
}