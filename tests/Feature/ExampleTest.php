<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_la_racine_redirige_vers_le_tableau_de_bord()
    {
        $response = $this->get('/');

        $response->assertRedirect('/dashboard');
    }

    public function test_un_visiteur_non_connecte_est_redirige_vers_la_connexion()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }
}
