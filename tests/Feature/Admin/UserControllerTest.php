<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    
    protected User $admin;
    protected User $regular;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'client']);
        
        // Usuario administrador
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        
        // Usuario no administrador
        $this->regular = User::factory()->create();
        $this->regular->assignRole('client');
    }
    
    /** @test */
    public function admin_can_access_user_index()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $response->assertStatus(200);
    }
    
    /** @test */
    public function admin_can_create_user()
    {
        $data = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'role' => 'client'
        ];
        
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), $data);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }
    
    /** @test */
    public function admin_can_view_user_details()
    {
        $user = User::factory()->create()->assignRole('client');
        
        $response = $this->actingAs($this->admin)->get(route('admin.users.show', $user->id));
        
        $response->assertStatus(200);
    }
    
    /** @test */
    public function admin_can_edit_user()
    {
        $user = User::factory()->create()->assignRole('client');
        
        $response = $this->actingAs($this->admin)->get(route('admin.users.edit', $user->id));
        $response->assertStatus(200);
    }
    
    /** @test */
    public function admin_can_update_user()
    {
        $user = User::factory()->create()->assignRole('client');
        
        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'client'
        ];
        
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $user->id), $data);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'updated@example.com']);
    }
    
    /** @test */
    public function admin_can_delete_user()
    {
        $user = User::factory()->create()->assignRole('client');
        
        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $user->id));
        
        $response->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
    
    /** @test */
    public function non_admin_cannot_perform_admin_actions()
    {
        $user = User::factory()->create();
        
        $this->actingAs($this->regular)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($this->regular)->post(route('admin.users.store'), [])->assertForbidden();
        $this->actingAs($this->regular)->get(route('admin.users.edit', $user->id))->assertForbidden();
        $this->actingAs($this->regular)->put(route('admin.users.update', $user->id), [])->assertForbidden();
        $this->actingAs($this->regular)->delete(route('admin.users.destroy', $user->id))->assertForbidden();
    }
}
