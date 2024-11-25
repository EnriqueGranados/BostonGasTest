<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Category;
use Tests\TestCase;

class CategoryTest extends TestCase
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

    //Test para verificar que el usuario al estar en el dashboard es capaz de redirigirse a la sección de categorias.
    public function test_usuario_se_redirige_a_la_seccion_de_categorias_desde_la_barra_de_navegacion()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.
        
        //Verificar que carga correctamente.
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        //Verificar que el enlace de "Administrar Categorías" está presente.
        $response->assertSee('Categorias');
        $response->assertSee(route('categories.index'));

        //Simular el clic en el enlace de "Administrar Categorías".
        $response = $this->get(route('categories.index'));
        $response->assertStatus(200);

        //Verificar que se redirige correctamente a la página de "Administrar Categorías"
        $response->assertSee('Administrar Categorías');
    }

    //Test para verificar que cargan las categorias existentes correctamente.
    public function test_cargar_categorias_correctamente()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        // Crear categorías de prueba
        $category1 = Category::factory()->create(['name' => 'Categoría 1']);
        $category2 = Category::factory()->create(['name' => 'Categoría 2']);

        //Acceder a la vista de categorías
        $response = $this->get(route('categories.index'));

        //Verificar que las categorías aparezcan en la respuesta
        $response->assertStatus(200);
        $response->assertSee('Categorías Existentes:');
        $response->assertSee($category1->name);
        $response->assertSee($category2->name);
    }

    //Test para verificar que el formulario para agregar categoria no debe enviarse vacio.
    public function test_no_enviar_formulario_vacio_para_agregar_categoria()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Acceder a la vista de categorías.
        $response = $this->get(route('categories.index'));

        //Enviar formulario vacío.
        $response = $this->post(route('categories.store'), []);

        //Verificar que la validación de 'name' falle (nombre de la categoria).
        $response->assertSessionHasErrors('name');
    }

    //Test para verificar que se pueden agregar categorias correctamente.
    public function test_agregar_categoria_y_verificar_visualizacion_en_la_vista()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Acceder a la vista de categorías.
        $response = $this->get(route('categories.index'));

        //Datos de la nueva categoría.
        $categoryData = ['name' => 'Nueva Categoría'];

        //Enviar el formulario para crear la categoría
        $response = $this->post(route('categories.store'), $categoryData);

        //Verificar que se redirige correctamente.
        $response->assertRedirect(route('categories.index'));

        //Verificar que un mensaje de éxito esté presente en la sesión
        $response->assertSessionHas('success', 'Categoría creada con éxito.');

        //Verificar que la categoría ha sido agregada a la base de datos.
        $this->assertDatabaseHas('categories', $categoryData);

        //Verificar que la categoría se muestra en la vista.
        $response = $this->get(route('categories.index'));
        $response->assertSee('Nueva Categoría');
    }

    //Test para verificar que pueden eliminar las categorias existentes correctamente.
    public function test_eliminar_categoria_correctamente()
    {
         //Crear un usuario.
         $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Acceder a la vista de categorías.
        $response = $this->get(route('categories.index'));

        //Crear una categoría de prueba.
        $category = Category::factory()->create(['name' => 'Categoría para Eliminar']);

        //Enviar la solicitud para eliminar la categoría.
        $response = $this->delete(route('categories.destroy', $category->id));

        //Verificar que la categoría ha sido eliminada.
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);

        //Verificar que se redirige correctamente.
        $response->assertRedirect(route('categories.index'));

        //Verificar que un mensaje de éxito esté presente en la sesión
        $response->assertSessionHas('success', 'Categoría eliminada correctamente.');
    }

    //Test para verificar que el usuario puede cerrar la sesión desde la vista de categorias.
    public function test_usuario_puede_cerrar_sesion_desde_la_vista_de_categorias()
    {
        //Crear usuario admin.
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user); //Autenticar usuario como admin.

        //Acceder a la vista de categorías.
        $response = $this->get(route('categories.index'));

        //Verificar que carga correctamente.
        $response->assertStatus(200);

        //Verificar que el nombre del usuario aparece en el botón.
        $response->assertSee($user->name);

        //Simular un clic en el botón del usuario (mostrar el dropdown).
        $response = $this->get(route('categories.index'), [
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