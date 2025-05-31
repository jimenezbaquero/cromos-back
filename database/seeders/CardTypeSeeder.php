<?php

namespace Database\Seeders;

use App\Models\CardType;
use Illuminate\Database\Seeder;

class CardTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CardType::create([
            'name' => 'Vertical',
            'height' => 350,
            'width' => 100,
        ]);
        
        CardType::create([
            'name' => 'Horizontal',
            'height' => 100,
            'width' => 350,
        ]);
    }
}
