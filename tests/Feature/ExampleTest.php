<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_redirects_to_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_login_page_is_accessible(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('SmartKas');
    }

    public function test_admin_dashboard_renders_cash_flow_and_status_charts(): void
    {
        $admin = User::where('username', 'admin')->first();

        $this->assertNotNull($admin, 'Akun admin tidak ditemukan. Jalankan seeder terlebih dahulu.');

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('adminKasChart', false)
            ->assertSee('adminStatusChart', false);
    }
}
