<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'rol' => 'estudiante',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // El login redirige directamente al dashboard del estudiante
        $response->assertRedirect('/student/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function admin_user_redirects_to_admin_dashboard()
    {
        $admin = User::factory()->create(['rol' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/dashboard');
        
        $response->assertRedirect('/admin/dashboard');
    }

    /** @test */
    public function developer_user_redirects_to_developer_dashboard()
    {
        $developer = User::factory()->create(['rol' => 'desarrollador']);
        
        $response = $this->actingAs($developer)->get('/dashboard');
        
        $response->assertRedirect('/developer/dashboard');
    }

    /** @test */
    public function student_user_redirects_to_student_dashboard()
    {
        $student = User::factory()->create(['rol' => 'estudiante']);
        
        $response = $this->actingAs($student)->get('/dashboard');
        
        $response->assertRedirect('/student/dashboard');
    }

    /** @test */
    public function user_can_logout()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user)->post('/logout');
        
        $this->assertGuest();
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}