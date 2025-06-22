<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario admin
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('1234'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('admin');

        // Crear 10 usuarios cliente
        $clients = User::factory(10)->create();
        
        $clients->each(function ($client) {
            $client->deposit(rand(1000,10000));
            $client->assignRole('client');
            $collectionIds = Collection::pluck('id')->toArray();
            $randomIds = collect($collectionIds)->shuffle()->take(rand(1, count($collectionIds)))->toArray();
            $client->collections()->sync($randomIds);
        });
        
    }
}
