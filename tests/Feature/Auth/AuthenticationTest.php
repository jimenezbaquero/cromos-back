<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthenticationTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'client']);
    }

    public function test_login_screen_can_be_rendered(): void {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_admins_can_authenticate_using_the_login_screen(): void {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->get(route('login'));
        $response->assertStatus(200);

        $response = $this->followingRedirects()->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
            '_token' => $this->app['session']->token(),
        ]);

        $this->assertAuthenticated();
        $response->assertStatus(200);
        $response->assertSee('Pages/Admin/Dashboard.vue');
    }

    public function test_clients_can_authenticate_using_the_login_screen(): void {
        $user = User::factory()->create();
        $user->assignRole('client');

        $response = $this->get(route('login'));
        $response->assertStatus(200);

        $response = $this->followingRedirects()->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
            '_token' => $this->app['session']->token(),
        ]);

        $this->assertAuthenticated();
        $response->assertStatus(200);
        $response->assertSee('Pages/Client/Dashboard.vue');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void {
        $user = User::factory()->create();

        $this->get('/');

        $response = $this->actingAs($user)
            ->followingRedirects()
            ->post(route('logout'), ['_token' => $this->app['session']->token()]);

        $this->assertGuest();

        $response->assertStatus(200);
        $response->assertSee('Welcome.vue');
    }
}
