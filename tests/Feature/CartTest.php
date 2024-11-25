<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Product;
use Tests\TestCase;

class CartTest extends TestCase
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

    //Test para verificar que el usuario es capaz de redirigirse al carrito de compras por medio de la barra de navegación, suponiendo que está en la sección de productos.
    public function test_usuario_se_redirige_al_carrito_de_compras_desde_la_barra_de_navegacion()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.
        
        //Verificar que el usuario este en la seccion de productos.
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);

        //Verificar que el enlace de "Carrito" está presente.
        $response->assertSee('Carrito');
        $response->assertSee(route('cart.show'));

        //Simular el clic en el enlace de "Carritos".
        $response = $this->get(route('cart.show'));
        $response->assertStatus(200);

        //Verificar que se redirige correctamente a la página de resumen de venta.
        $response->assertSee('Resumen de la Venta');
    }

    //Test para verificar que si no hay productos en el carrito se muestre el carrito vacío.
    public function test_carrito_de_compras_vacio_si_no_hay_productos_agregados()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Acceder al carrito de compras.
        $response = $this->get(route('cart.show'));
        $response->assertStatus(200);

        //Verificar que el mensaje de "El carrito se encuentra vacío." esté presente.
        $response->assertSee('El carrito se encuentra vacío.');
    }

    //Test para verificar que se cargue la tabla de productos correctamente con toda su información.
    public function test_tabla_de_resumen_de_venta_se_carga_correctamente()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Crear productos de prueba.
        $product1 = Product::create([
            'name' => 'Producto A',
            'price' => 100,
            'stock' => 10,
            'category' => 'Categoría 1',
            'image' => 'producto_a.jpg',
            'description' => 'Descripcion',
        ]);

        $product2 = Product::create([
            'name' => 'Producto B',
            'price' => 150,
            'stock' => 5,
            'category' => 'Categoría 2',
            'image' => 'producto_b.jpg',
            'description' => 'Descripcion', 
        ]);

        // Agregar productos al carrito simulando la sesión
        Session::put('cart', [
            $product1->id => [
                'name' => $product1->name,
                'price' => $product1->price,
                'quantity' => 2,
                'image' => $product1->image,
            ],
            $product2->id => [
                'name' => $product2->name,
                'price' => $product2->price,
                'quantity' => 1,
                'image' => $product2->image,
            ]
        ]);

        //Acceder al carrito de compras.
        $response = $this->get(route('cart.show'));
        $response->assertStatus(200);

        //Verificar que los productos estén en la vista.
        $response->assertSee($product1->name);
        $response->assertSee($product2->name);
        $response->assertSee('$' . number_format($product1->price, 2));
        $response->assertSee('$' . number_format($product2->price, 2));
        $response->assertSee('2'); //Cantidad del Producto A.
        $response->assertSee('1'); //Cantidad del Producto B.

        //Verificar los subtotales y el total.
        $subtotal1 = $product1->price * 2;
        $subtotal2 = $product2->price * 1;
        $total = $subtotal1 + $subtotal2;
        $response->assertSee('$' . number_format($subtotal1, 2));
        $response->assertSee('$' . number_format($subtotal2, 2));
        $response->assertSee('$' . number_format($total, 2));
    }

    //Test para verificar que se puede eliminar un producto del carrito de compras.
    public function test_eliminar_producto_del_carrito_de_compras()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Crear productos de prueba.
        $product = Product::create([
            'name' => 'Producto A',
            'price' => 100,
            'stock' => 10,
            'category' => 'Categoría 1',
            'image' => 'producto_a.jpg',
            'description' => 'Descripcion',
        ]);

        //Agregar el producto al carrito (simulando la acción de agregar al carrito).
        $response = $this->post(route('cart.add', $product->id), [
            'quantity' => 2
        ]);

        //Acceder al carrito de compras.
        $response = $this->get(route('cart.show'));
        $response->assertStatus(200);

        //Realizar la solicitud para eliminar el producto del carrito.
        $response = $this->post(route('cart.remove', $product->id));

        //Verificar que el producto ha sido eliminado del carrito.
        $cart = session('cart');
        $this->assertArrayNotHasKey($product->id, $cart);
        $this->assertCount(0, $cart);
    
        //Verificar mensaje de éxito.
        $response->assertRedirect(route('cart.show'));
        $response->assertSessionHas('success', 'Producto eliminado del carrito.');
    }

    //Test para verificar que la venta no se realiza si el formulario está vacío.
    public function test_formulario_de_informacion_de_la_venta_no_se_envia_estando_vacio()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Acceder al carrito de compras.
        $response = $this->get(route('cart.show'));
        $response->assertStatus(200);

        //Simular un envío del formulario sin datos.
        $response = $this->post(route('sales.store'), [
            'customer' => '', //Nombre del Cliente vacío.
            'dui' => '', //DUI vacío.
            'payment' => '', //Método de pago vacío.
            'seller' => auth()->user()->name . ' ' . auth()->user()->last_name, //Vendedor.
        ]);

        //Verificar que no se haya realizado la venta.
        $response->assertSessionHasErrors(['customer', 'dui', 'payment']);
        $response->assertStatus(302); //Asegurarse de que es un redireccionamiento.
    }

    //Test para verificar que se realiza la venta correctamente.
    public function test_venta_realizada_correctamente_al_tener_el_formulario_completo()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Acceder al carrito de compras.
        $response = $this->get(route('cart.show'));
        $response->assertStatus(200);

        //Crear los datos completos para enviar el formulario.
        $sale = [
            'customer' => 'Juan Pérez',
            'dui' => '12345678-9',
            'payment' => 'Efectivo',
            'seller' => auth()->user()->name . ' ' . auth()->user()->last_name,
        ];

        //Simular el envío de la venta.
        $response = $this->post(route('sales.store'), $sale);

        //Asegurarse de que la venta fue registrada en la base de datos.
        $this->assertDatabaseHas('sales', [
            'customer' => 'Juan Pérez',
            'payment' => 'Efectivo',
        ]);

        //Verificar mensaje de éxito.
        $response->assertSessionHas('success', 'Venta registrada con éxito. El resumen de compra ha sido limpiado.');
    }

    //Test para verificar que el usuario puede cerrar la sesión desde la vista del carrito de compras.
    public function test_usuario_puede_cerrar_sesion_desde_el_carrito_de_compras()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Acceder a la página de carrito de compras.
        $response = $this->get(route('cart.show'));

        //Verificar que carga correctamente.
        $response->assertStatus(200);

        //Verificar que el nombre del usuario aparece en el botón.
        $response->assertSee($user->name);

        //Simular un clic en el botón del usuario (mostrar el dropdown).
        $response = $this->get(route('cart.show'), [
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