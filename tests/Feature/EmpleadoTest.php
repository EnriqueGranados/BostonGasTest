<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class EmpleadoTest extends TestCase
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

    //Test para verificar que el usuario al estar en el dashboard es capaz de redirigirse a la sección de administrar empleados.
    public function test_usuario_administrador_se_redirige_a_la_seccion_de_administrar_empleados_desde_la_barra_de_navegacion()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.
        
        //Verificar que carga correctamente.
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        //Verificar que el enlace de "Administrar Empleados" está presente.
        $response->assertSee('Empleados');
        $response->assertSee(route('users.create'));

        //Simular el clic en el enlace de "Administrar Empleados".
        $response = $this->get(route('users.create'));
        $response->assertStatus(200);

        //Verificar que se redirige correctamente a la página de "Administrar Empleados"
        $response->assertSee('Administrar Empleados');
    }

    public function test_ver_listado_de_empleados_existentes()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Crear algunos empleados de prueba.
        $user1 = User::create([
            'name' => 'Juan',
            'last_name' => 'Pérez',
            'phone_number' => '1234-5678',
            'email' => 'juan@example.com',
            'password' => 'password123',
        ]);

        $user2 = User::create([
            'name' => 'Maria',
            'last_name' => 'González',
            'phone_number' => '9876-5432',
            'email' => 'maria@example.com',
            'password' => 'password123',
        ]);

        //Ir a la vista donde se listan los empleados.
        $response = $this->get(route('users.create'));

        //Verificar que los usuarios creados aparecen en la tabla.
        $response->assertSee($user1->name);
        $response->assertSee($user1->last_name);
        $response->assertSee($user1->phone_number);
        $response->assertSee($user1->email);

        $response->assertSee($user2->name);
        $response->assertSee($user2->last_name);
        $response->assertSee($user2->phone_number);
        $response->assertSee($user2->email);
    }

    public function test_comprobar_que_se_pueden_eliminar_empleados_del_sistema()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Crear un empleado de prueba para eliminar.
        $user1 = User::create([
            'name' => 'Carlos',
            'last_name' => 'Sánchez',
            'phone_number' => '5551-2345',
            'email' => 'carlos@example.com',
            'password' => 'password123',
        ]);

        //Verificar que el empleado fue creado y existe en la base de datos.
        $this->assertDatabaseHas('users', [
            'email' => 'carlos@example.com'
        ]);

        //Realizar la solicitud DELETE para eliminar al empleado.
        $response = $this->delete(route('users.destroy', $user1->id));

        //Verificar que el empleado ya no existe en la base de datos.
        $this->assertDatabaseMissing('users', [
            'email' => 'carlos@example.com'
        ]);

        //Verificar que el sistema redirige correctamente después de la eliminación.
        $response->assertRedirect(route('users.create'));
    }

    public function test_editar_informacion_de_un_empleado_existente()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Crear un empleado de prueba.
        $user = User::create([
            'name' => 'Carlos',
            'last_name' => 'Sánchez',
            'phone_number' => '5551-2345',
            'email' => 'carlos@example.com',
            'password' => 'password123',
        ]);

        //Verificar que el empleado existe en la base de datos.
        $this->assertDatabaseHas('users', [
            'email' => 'carlos@example.com'
        ]);

        //Acceder a la página de edición.
        $response = $this->get(route('users.edit', $user->id));

        //Verificar que la vista de edición carga correctamente.
        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($user->last_name);
        $response->assertSee($user->phone_number);

        //Modificar la información del empleado.
        $updatedData = [
            'name' => 'Carlos Eduardo',
            'last_name' => 'Sánchez Pérez',
            'phone_number' => '5556-5432',
            'address' => '456 Nueva Calle',
            'birth_date' => '1989-12-12',
            'gender' => 'male',
        ];

        //Realizar la solicitud PUT para actualizar la información del empleado.
        $response = $this->put(route('users.update', $user->id), $updatedData);

        //Verificar que los datos han sido actualizados correctamente en la base de datos.
        $this->assertDatabaseHas('users', [
            'name' => 'Carlos Eduardo',
            'last_name' => 'Sánchez Pérez',
            'phone_number' => '5556-5432',
            'address' => '456 Nueva Calle',
            'birth_date' => '1989-12-12',
            'gender' => 'male',
        ]);

        //Verificar que el sistema redirige correctamente después de la actualización.
        $response->assertRedirect(route('users.create'));

        //Verificar que un mensaje de éxito esté presente en la sesión.
        $response->assertSessionHas('success', 'Usuario actualizado con éxito.');

        //Acceder a la página de listado de usuarios y verificar que el empleado tiene los nuevos datos.
        $response = $this->get(route('users.create'));
        $response->assertSee('Carlos Eduardo');
        $response->assertSee('Sánchez Pérez');
        $response->assertSee('5556-5432');
    }

    //Test para verificar que no se envia el formulario para agregar un nuevo empleado.
    public function test_no_se_envia__el_formulario_para_agregar_producto_si_los_campos_estan_vacios()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Acceder a la página de administrar empleados.
        $response = $this->get(route('users.create'));
        $response->assertStatus(200);

        //Simular la solicitud POST con todos los campos vacíos.
        $response = $this->post(route('users.store'), []);

        //Verificar que el formulario no se envía y se redirige de vuelta con errores.
        $response->assertSessionHasErrors(['name', 'last_name', 'phone_number', 'email', 'password']);
        $response->assertRedirect();
    }

    //Test para verificar que se agrega un nuevo empleado correctamente.
    public function test_agregar_un_nuevo_empleado_correctamente()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Acceder a la página de administrar empleados.
        $response = $this->get(route('users.create'));
        $response->assertStatus(200);

        //Colocar los datos del empleado a enviar.
        $user1 = [
            'name' => 'Carlos',
            'last_name' => 'Sánchez',
            'phone_number' => '5551-2345',
            'email' => 'carlos@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        //Simular la solicitud POST para agregar el empleado.
        $response = $this->post(route('users.store'), $user1);

        //Verificar redireccionamiento.
        $response->assertRedirect(route('users.create'));

        //Verificar que un mensaje de éxito esté presente en la sesión.
        $response->assertSessionHas('success', 'Usuario creado con éxito.');

        //Verificar que los datos han sido actualizados correctamente en la base de datos.
        $this->assertDatabaseHas('users', [
            'name' => 'Carlos',
            'last_name' => 'Sánchez',
            'phone_number' => '5551-2345',
            'email' => 'carlos@example.com',
        ]);
    }

    //Test para verificar que el usuario puede cerrar la sesión desde la vista de administrar empleados.
    public function test_usuario_puede_cerrar_sesion_desde_la_vista_de_administrar_empleados()
    {
        //Crear usuario admin.
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user); //Autenticar usuario como admin.

        //Acceder a la página de administrar empleados.
        $response = $this->get(route('users.create'));
        $response->assertStatus(200);

        //Verificar que el nombre del usuario aparece en el botón.
        $response->assertSee($user->name);

        //Simular un clic en el botón del usuario (mostrar el dropdown).
        $response = $this->get(route('users.create'), [
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