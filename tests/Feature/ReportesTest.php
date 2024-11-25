<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Sale;
use Tests\TestCase;

class ReportesTest extends TestCase
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

    //Test para verificar que los usuarios con rol de empleado no pueden redirigirse al reporte de ventas por medio de la barra de navegación.
    public function test_usuario_con_rol_de_empleado_no_puede_redirigirse_a_la_opcion_de_reporte_de_ventas()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.
        
        //Verificar que el usuario este en el dashboard.
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        //Verificar que el enlace de "Reporte de Ventas" no está presente.
        $response->assertDontSee(route('reporte.index'));
    }

    //Test para verificar que el usuario es capaz de redirigirse al reporte de ventas por medio de la barra de navegación, suponiendo que es usuario administrador.
    public function test_usuario_se_redirige_a_la_seccion_de_reporte_de_ventas_desde_la_barra_de_navegacion_si_es_administrador()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.
        
        //Verificar que el usuario este en el dashboard.
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        //Verificar que el enlace de "Reporte de Ventas" está presente.
        $response->assertSee('Reporte de Ventas');
        $response->assertSee(route('reporte.index'));

        //Simular el clic en el enlace de "Reporte de Ventas".
        $response = $this->get(route('reporte.index'));
        $response->assertStatus(200);

        //Verificar que se redirige correctamente la página de reporte de ventas.
        $response->assertSee('Reporte de Ventas');
    }

    //Test para verificar que los reportes se generen correctamente sea cual sea el tiempo seleccionado.
    public function test_generar_reporte_de_ventas()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Obtener la vista donde se listan las ventas.
        $response = $this->get(route('reporte.index'));

        //Crear diferentes registros para probar cada periodo de tiempo.
        Sale::factory()->count(5)->create(['created_at' => now()]);
        Sale::factory()->count(3)->create(['created_at' => now()->subWeek()]);
        Sale::factory()->count(2)->create(['created_at' => now()->subMonth()]);
        Sale::factory()->count(1)->create(['created_at' => now()->subYear()]);

        $tipos = ['diario', 'semanal', 'mensual', 'anual'];

        //Probamos cada tipo de reporte.
        foreach ($tipos as $tipo) {
            $response = $this->post(route('reportes.generate'), [
                'tipo' => $tipo,
            ]);

            $response->assertStatus(200); //Verificar que carga correctamente.
        }
    }

    //Test para verificar que los reportes generados en PDF son capaces de descargarse.
    public function test_descargar_reporte_pdf_generado()
    {
        //Simula un usuario autenticado si la ruta requiere autenticación.
        $user = User::factory()->create();
        $this->actingAs($user);

        //Simular datos en la base de datos.
        Sale::factory()->create([
            'customer' => 'Juan Pérez',
            'payment' => 'Efectivo',
            'total' => 100.00,
            'created_at' => now(),
        ]);

        //Obtener la vista donde se listan las ventas.
        $response = $this->get(route('reporte.index'));

        //Generar el PDF y verificar la respuesta.
        $response = $this->get(route('reportes.pdf', ['tipo' => 'diario']));

        //Verificar que la respuesta sea un PDF y sea redirigido a dicho documento.
        $response->assertStatus(302);
    }

    //Test para verificar que el usuario puede cerrar la sesión desde la vista de reportes.
    public function test_usuario_puede_cerrar_sesion_desde_la_vista_de_reportes()
    {
        //Crear usuario admin.
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user); //Autenticar usuario como admin.

        //Obtener la vista donde se listan las ventas.
        $response = $this->get(route('reporte.index'));

        //Verificar que carga correctamente.
        $response->assertStatus(200);

        //Verificar que el nombre del usuario aparece en el botón.
        $response->assertSee($user->name);

        //Simular un clic en el botón del usuario (mostrar el dropdown).
        $response = $this->get(route('reporte.index'), [
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