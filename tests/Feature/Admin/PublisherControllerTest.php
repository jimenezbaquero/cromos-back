<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Publisher as Publisher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PublisherControllerTest extends TestCase
{
    use RefreshDatabase;
    
    protected User $admin;
    protected User $client;
    
    protected function setUp(): void {
        parent::setUp();
        
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'client']);
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        
        $this->client = User::factory()->create();
        $this->client->assignRole('client');
    }
    
    /** @test */
    public function admin_can_access_publisher_index() {
        $response = $this->actingAs($this->admin)->get(route('admin.publishers.index'));
        
        $response->assertStatus(200);
    }
    
    /** @test */
    public function admin_can_access_create_view() {
        $response = $this->actingAs($this->admin)->get(route('admin.publishers.create'));
        
        $response->assertStatus(200);
        $response->assertSee('Pages/Admin/Publishers/Create.vue');
    }
    
    /** @test */
    public function admin_can_create_publisher() {
        $response = $this->actingAs($this->admin)->post(route('admin.publishers.store'), [
            'name' => 'Nueva Editorial',
        ]);
        
        $response->assertRedirect(route('admin.publishers.index'));
        $this->assertDatabaseHas('publishers', ['name' => 'Nueva Editorial']);
    }
    
    /** @test */
    public function admin_can_access_edit_view() {
        $publisher = Publisher::factory()->create();
        
        $response = $this->actingAs($this->admin)->get(route('admin.publishers.edit', $publisher->id));
        
        $response->assertStatus(200);
        $response->assertSee('Pages/Admin/Publishers/Edit.vue');
    }
    
    /** @test */
    public function admin_can_edit_publisher() {
        $response = $this->actingAs($this->admin)->post(route('admin.publishers.store'), [
            'name' => 'Editorial Editada',
        ]);
        
        $response->assertRedirect(route('admin.publishers.index'));
        $this->assertDatabaseHas('publishers', ['name' => 'Editorial Editada']);
    }
    
    /** @test */
    public function it_does_not_allow_duplicate_name()
    {
        Publisher::factory()->create([
            'name' => 'name example',
        ]);
        
        $response = $this->actingAs($this->admin)->post('/admin/publishers', [
            'name' => 'name example',
        ]);
        
        
        $response->assertSessionHasErrors('name');
    }
    
    /** @test */
    public function non_admin_cannot_perform_admin_actions() {
        $publisher = Publisher::factory()->create();
        
        $this->actingAs($this->client)->get(route('admin.publishers.index'))->assertForbidden();
        $this->actingAs($this->client)->post(route('admin.publishers.store'), [])->assertForbidden();
        $this->actingAs($this->client)->get(route('admin.publishers.edit', $publisher->id))->assertForbidden();
        $this->actingAs($this->client)->put(route('admin.publishers.update', $publisher->id), [])->assertForbidden();
    }
}