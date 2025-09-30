<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
   
    public function up_endpoint_returns_200()
    {
        $this->get('/up')->assertStatus(200);
    }


    public function login_page_is_reachable()
    {
        $this->get('/login')->assertStatus(200);
    }

    public function products_requires_auth_and_redirects_to_login()
    {
        $response = $this->get('/products');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
