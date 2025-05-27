<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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

        foreach ($clients as $client) {
            $client->assignRole('client');
        }
    }
}
