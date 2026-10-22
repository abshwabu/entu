<?php

namespace Tests\Feature;

use Tests\TestCase;

class FilamentAdminTest extends TestCase
{
    public function test_admin_redirects_to_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_admin_login_page_loads_successfully(): void
    {
        $response = $this->get('/admin/login');

        $response->assertSuccessful();
        $response->assertSee('Sign in');
    }
}
