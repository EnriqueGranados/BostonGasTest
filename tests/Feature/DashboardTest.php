<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Sale;
use App\Models\Product;
use Tests\TestCase;

class DashboardTest extends TestCase
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

    //Test para verificar si la barra de progreso no se actualiza si no hay ventas.
    public function test_barra_de_progreso_de_la_meta_diaria_no_muestra_avance_si_no_hay_ventas_realizadas_en_el_dia()
    {
        //Crear un usuario autenticado.
        $user = User::factory()->create();
        $this->actingAs($user);

        $salesToday = 0.00; //Total de ventas de ejemplo.

        //Realizar la petición a la página que muestra la barra.
        $response = $this->get(route('dashboard'));

        //Verificar que carga correctamente.
        $response->assertStatus(200);

        //Verificar que el valor "Ventas de Hoy: $--.-" se muestre en cero si no hay ventas.
        $response->assertSeeText('Ventas de Hoy: $' . number_format($salesToday, 2));

        //Verificar que la barra de progreso no avance si no hay ventas.
        $response->assertSee('<div id="progressBar" class="bg-teal-500 h-2" style="width: 0% !important; background-color: #38b2ac;">', false); // Verificar barra en 0%
        
        //No se debería mostrar este mensaje.
        $response->assertDontSee('¡Meta diaria alcanzada!'); 
    } 

    //Test para verificar si la barra de progreso se actualiza según el total de las ventas realizadas, sin mostrar el mensaje de completado sin haber llegado a la meta.
    public function test_actualizacion_de_barra_de_progreso_de_la_meta_diaria_con_ventas_realizadas_en_el_dia()
    {
        //Crear un usuario autenticado.
        $user = User::factory()->create();
        $this->actingAs($user);

        //Simular ventas en el sistema.
        $sales = Sale::factory()->count(2)->make([
            'total' => 15.00,  //Asignar 15.00 a cada venta para que el total sea 30.00.
        ]);

        //Guardar la venta en la base de datos.
        Sale::insert($sales->toArray());

        //Sumar el total de las ventas.
        $salesToday = $sales->sum('total');

        $expectedProgress = min(100, ($salesToday / 250) * 100); //Cálculo del progreso esperado.

        //Realizar la petición a la página que muestra la barra.
        $response = $this->get(route('dashboard'));

        //Verificar que carga correctamente.
        $response->assertStatus(200);

        //Verificar que el valor "Ventas de Hoy: $--.-" se muestre correctamente según las ventas realizadas.
        $response->assertSeeText('Ventas de Hoy: $' . number_format($salesToday, 2));

        //Verificar que la barra de progreso cargue según el total de las ventas.
        $response->assertSee('<div id="progressBar" class="bg-teal-500 h-2" style="width: ' . (($salesToday / 250) * 100) . '% !important; background-color: #38b2ac;">', false);
        
        //No se debería mostrar este mensaje.
        $response->assertDontSee('¡Meta diaria alcanzada!'); 
    } 

    //Test para verificar si la barra de progreso se carga al 100% al alcanzar la meta diaria, mostrando el mensaje.
    public function test_barra_de_progreso_de_la_meta_diaria_completa_mostrando_el_mensaje_al_alcanzar_la_meta_diaria()
    {
        //Crear un usuario autenticado.
        $user = User::factory()->create();
        $this->actingAs($user);

        //Simular ventas en el sistema que sumen exactamente 250.00.
        $sales = Sale::factory()->count(2)->make([
            'total' => 125.00,  //Asignar 125.00 a cada venta para que el total sea 250.00.
        ]);

        //Guardar la venta en la base de datos.
        Sale::insert($sales->toArray());

        //Sumar el total de las ventas.
        $salesToday = $sales->sum('total');

        //Realizar la petición a la página que muestra la barra.
        $response = $this->get(route('dashboard'));

        //Verificar que carga correctamente.
        $response->assertStatus(200);

        //Verificar que el valor "Ventas de Hoy: $250.00" se muestre correctamente.
        $response->assertSeeText('Ventas de Hoy: $' . number_format($salesToday, 2));

        //Verificar que la barra de progreso esté al 100%.
        $response->assertSee('<div id="progressBar" class="bg-teal-500 h-2" style="width: 100% !important; background-color: #38b2ac;">', false);

        //Verificar que el mensaje de "Meta diaria alcanzada" esté presente.
        $response->assertSee('¡Meta diaria alcanzada!');
    }

    //Test para verificar si se muestran correctamente las últimas cinco ventas realizadas en la tabla correspondiente.
    public function test_mostrar_tabla_con_las_ultimas_cinco_ventas_realizadas()
    {
        //Crear un usuario autenticado.
        $user = User::factory()->create();
        $this->actingAs($user);

        //Crear ventas de ejemplo.
        $sales = Sale::factory()->count(6)->create();

        //Obtener las últimas 5 ventas.
        $latestSales = $sales->sortByDesc('created_at')->take(5);

        //Realizar la petición a la página que muestra la tabla.
        $response = $this->get(route('dashboard'));

        //Verificar que carga correctamente.
        $response->assertStatus(200);

        //Verificar que la tabla de ventas existe.
        $response->assertSee('Últimas 5 Ventas');
        //Confirmar que existe una tabla.
        $response->assertSee('<table', false); 
        $response->assertSee('<thead', false);
        $response->assertSee('<tbody', false);

        //Validar las filas de la tabla.
        foreach ($latestSales as $sale) {
            $response->assertSee($sale->id);
            $response->assertSee($sale->seller);
            $response->assertSee($sale->customer);
            $response->assertSee($sale->payment);
            $response->assertSee(number_format($sale->total, 2));
            $response->assertSee($sale->created_at->format('d-m-Y H:i'));
        }
    }

    //Test para verificar si se muestran correctamente los productos con menos stock en la tabla correspondiente.
    public function test_mostrar_correctamente_los_productos_con_menos_stock()
    {
        //Crear un usuario autenticado.
        $user = User::factory()->create();
        $this->actingAs($user);

        //Crear productos con diferentes niveles de stock.
        $products = Product::factory()->count(5)->create([]);

        //Obtenemos los productos con stock menor a 20.
        $lowStockProducts = Product::where('stock', '<', 20)->get();

        //Realizar la petición a la página que muestra los productos con bajo stock.
        $response = $this->get(route('dashboard'));

        //Verificar que carga correctamente.
        $response->assertStatus(200);

        //Verificar que la tabla de productos con bajo stock esté presente.
        $response->assertSee('Productos con Menos Stock');
        //Confirmar que existe una tabla.
        $response->assertSee('<table', false); 
        $response->assertSee('<thead', false);
        $response->assertSee('<tbody', false);

        //Verificar que los productos con bajo stock estén listados.
        foreach ($lowStockProducts as $product) {
            $response->assertSee($product->id);
            $response->assertSee($product->name);
            $response->assertSee($product->description);
            $response->assertSee($product->category);
            $response->assertSee($product->stock);

            //Verificar que el color del stock sea el adecuado.
            if ($product->stock <= 10) {
                $response->assertSee('text-red-600'); //Debería ser rojo para stock muy bajo.
            } elseif ($product->stock <= 50) {
                $response->assertSee('text-orange-500'); //Naranja para stock medio.
            } else {
                $response->assertSee('text-green-600'); //Verde para stock alto.
            }
        }
    }

    //Test para verificar que el usuario puede cerrar la sesión desde el dashboard.
    public function test_usuario_puede_cerrar_sesion_desde_el_dashboard()
    {
        //Crear un usuario autenticado.
        $user = User::factory()->create();
        $this->actingAs($user);

        //Realizar la petición a la vista del dashboard.
        $response = $this->get(route('dashboard'));

        //Verificar que carga correctamente.
        $response->assertStatus(200);

        //Verificar que el nombre del usuario aparece en el botón.
        $response->assertSee($user->name);

        //Simular un clic en el botón del usuario (mostrar el dropdown).
        $response = $this->get(route('dashboard'), [
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