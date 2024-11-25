<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    //Test para verificar que la página del login cargue correctamente.
    public function test_pagina_de_inicio_de_sesion_carga_correctamente()
    {
        //Verifica que la página de bienvenida cargue correctamente
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    //Test para verificar que el usuario puede acceder con sus credenciales correctas.
    public function test_usuario_es_capaz_de_acceder_con_credenciales_correctas()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'), //La contraseña estará encriptada.
        ]);

        //Realizar una solicitud POST al endpoint del login.
        $response = $this->post('/login', [ //Aquí se define correctamente las credenciales, como ejemplo se hace coincidir con las creadas anteriormente.
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        //Verificar que el usuario fue autenticado correctamente.
        $response->assertRedirect('/dashboard'); //Manda al dashboard si todo sale correcto.
        $this->assertAuthenticatedAs($user);
    }

    //Test para verificar que el usuario no puede acceder con sus credenciales incorrectas.
    public function test_usuario_no_puede_acceder_con_credenciales_incorrectas()
    {
        //Crear un usuario con credenciales válidas.
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'), //La contraseña estará encriptada.
        ]);

        //Intentar iniciar sesión con credenciales incorrectas.
        $response = $this->post('/login', [
            'email' => 'test@example.com', // El correo está correcto.
            'password' => 'wrongpassword', // La contraseña está incorrecta.
        ]);

        //Verificar que el usuario no ha sido autenticado.
        $response->assertRedirect('/'); //Se refresca la página y muestra el mensaje de error de credenciales.
        $this->assertGuest(); //Verifica que el usuario no esté autenticado.
    }
}