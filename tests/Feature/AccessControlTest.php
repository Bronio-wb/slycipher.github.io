<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_access_admin_routes()
    {
        $admin = User::factory()->create(['rol' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function developer_cannot_access_admin_routes()
    {
        $developer = User::factory()->create(['rol' => 'desarrollador']);
        
        $response = $this->actingAs($developer)->get('/admin/dashboard');
        
        $response->assertStatus(403);
    }

    /** @test */
    public function student_cannot_access_admin_routes()
    {
        $student = User::factory()->create(['rol' => 'estudiante']);
        
        $response = $this->actingAs($student)->get('/admin/dashboard');
        
        $response->assertStatus(403);
    }

    /** @test */
    public function student_cannot_access_developer_routes()
    {
        $student = User::factory()->create(['rol' => 'estudiante']);
        
        $response = $this->actingAs($student)->get('/developer/dashboard');
        
        $response->assertStatus(403);
    }

    /** @test */
    public function developer_can_access_developer_routes()
    {
        $developer = User::factory()->create(['rol' => 'desarrollador']);
        
        $response = $this->actingAs($developer)->get('/developer/dashboard');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function guest_cannot_access_protected_routes()
    {
        $response = $this->get('/admin/dashboard');
        
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_users_can_access_shared_routes()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/cursos');
        
        $response->assertStatus(200);
    }
}