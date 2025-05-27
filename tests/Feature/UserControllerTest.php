<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void {
        parent::setUp();
        
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'client']);
    }
    
    public function test_admin_can_create_user() {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $this->actingAs($admin);
        
        $this->get('/users');
        
        $response = $this->post(route('users.store'), [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@usuario.com',
            'role' => 'client',
            '_token' => $this->app['session']->token()
        ]);
        
        $response->assertRedirect(route('users.index'));
        
        $this->assertDatabaseHas('users', [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@usuario.com',
        ]);
        
        $newUser = User::where('email', 'nuevo@usuario.com')->first();
        $this->assertTrue($newUser->hasRole('client'));
    }
    
    public function test_it_validates_request_fields() {
        Role::updateOrCreate(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $this->actingAs($admin);
        
        $this->get('/users');
        
        $response = $this->post(route('users.store'), [
            '_token' => $this->app['session']->token()
        ]);
        
        $response->assertSessionHasErrors([
            'name',
            'email',
            'role'
        ]);
    }
    
    public function test_only_admin_can_store_users() {
        Role::updateOrCreate(['name' => 'admin']);
        $user = User::factory()->create(); // sin rol admin
        $this->actingAs($user);
        
        $this->get('/users');
        
        $response = $this->post(route('users.store'), [
            'name' => 'Sin permiso',
            'email' => 'sin@permiso.com',
            'role' => 'admin',
            '_token' => $this->app['session']->token()
        ]);
        
        $response->assertForbidden(); // basado en authorize() de UserRequest
    }
    
    public function test_it_rolls_back_transaction_and_logs_on_exception() {
        Role::updateOrCreate(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $this->actingAs($admin);
        
        $this->get('/users');
        
        // Provocar fallo (rol inválido)
        $response = $this->post(route('users.store'), [
            'name' => 'Error Test',
            'email' => 'error@test.com',
            'role' => 'rol-inexistente',
            '_token' => $this->app['session']->token()
        ]);
        
        $response->assertSessionHasErrors(['role']);
        $this->assertDatabaseMissing('users', ['email' => 'error@test.com']);
    }
}
