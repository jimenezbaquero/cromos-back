<?php

namespace Tests\Feature\Admin;

use App\Models\Collection;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CollectionControllerTest extends TestCase
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
    public function admin_can_access_index() {
        $response = $this->actingAs($this->admin)->get(route('admin.collections.index'));
        $response->assertStatus(200);
    }
    
    /** @test */
    public function admin_can_access_create_form() {
        $response = $this->actingAs($this->admin)->get(route('admin.collections.create'));
        $response->assertStatus(200);
    }
    
    /** @test */
    public function admin_can_store_collection() {
        $publisher = Publisher::factory()->create();
        
        $data = [
            'name' => 'Test Collection',
            'description' => 'A sample description',
            'year' => 2024,
            'publisher_id' => $publisher->id,
        ];
        
        $response = $this->actingAs($this->admin)->post(route('admin.collections.store'), $data);
        
        $response->assertRedirect(route('admin.collections.index'));
        $this->assertDatabaseHas('collections', ['name' => 'Test Collection']);
    }
    
    /** @test */
    public function admin_can_view_a_collection() {
        $collection = Collection::factory()->create();
        
        $response = $this->actingAs($this->admin)->get(route('admin.collections.show', $collection));
        $response->assertStatus(200);
    }
    
    /** @test */
    public function admin_can_access_edit_form() {
        $collection = Collection::factory()->create();
        
        $response = $this->actingAs($this->admin)->get(route('admin.collections.edit', $collection));
        $response->assertStatus(200);
    }
    
    /** @test */
    public function admin_can_update_collection() {
        $collection = Collection::factory()->create();
        $newData = [
            'name' => 'Updated Name',
            'description' => 'Updated Description',
            'year' => 2025,
            'publisher_id' => Publisher::factory()->create()->id,
        ];
        
        $response = $this->actingAs($this->admin)->put(route('admin.collections.update', $collection), $newData);
        
        $response->assertRedirect(route('admin.collections.index'));
        $this->assertDatabaseHas('collections', ['name' => 'Updated Name']);
    }
    
    /** @test */
    public function admin_can_delete_collection() {
        $collection = Collection::factory()->create();
        
        $response = $this->actingAs($this->admin)->delete(route('admin.collections.destroy', $collection));
        
        $response->assertRedirect(route('admin.collections.index'));
        $this->assertDatabaseMissing('collections', ['id' => $collection->id]);
    }
    
    /** @test */
    public function non_admin_users_cannot_access_admin_routes() {
        $collection = Collection::factory()->create();
        
        $this->actingAs($this->client)->get(route('admin.collections.index'))->assertForbidden();
        $this->actingAs($this->client)->get(route('admin.collections.create'))->assertForbidden();
        $this->actingAs($this->client)->post(route('admin.collections.store'), [])->assertForbidden();
        $this->actingAs($this->client)->get(route('admin.collections.edit', $collection))->assertForbidden();
        $this->actingAs($this->client)->put(route('admin.collections.update', $collection), [])->assertForbidden();
        $this->actingAs($this->client)->delete(route('admin.collections.destroy', $collection))->assertForbidden();
    }
}
