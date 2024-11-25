<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class WelcomeTest extends TestCase
{
    use RefreshDatabase;

    //Test para verificar que la página de bienvenida cargue correctamente.
    public function test_pagina_de_bienvenida_carga_correctamente()
    {
        //Verifica que la página de bienvenida cargue correctamente
        $response = $this->get(route('welcome'));
        $response->assertStatus(200); //La página debe devolver un código HTTP 200.
    }

    //Test para verificar que el botón de la página de bienvenida redirige al login si no hay sesión iniciada.
    public function test_boton_redirige_al_login_si_no_esta_autenticado()
    {
        //Asegúrate de que el usuario no esté autenticado.
        $this->assertFalse(Auth::check());

        //Accede a la página de bienvenida.
        $response = $this->get(route('welcome'));

        //Verifica que el enlace de "Iniciar Sesión" redirija a la página de login.
        $response->assertSee(route('login')); //Verifica que el enlace de login esté presente.
    }

    //Test para verificar que el botón redirige al dashboard si hay sesión iniciada.
    public function test_boton_redirige_al_dashboard_si_ya_hay_sesion_iniciada()
    {
        // Crea un usuario y autentícalo
        $user = User::factory()->create();
        $this->actingAs($user); //Simula que el usuario está autenticado.

        //Accede a la página de bienvenida.
        $response = $this->get(route('welcome'));

        //Verifica que el botón redirige al dashboard.
        $response->assertSee(route('dashboard'));
    }
}