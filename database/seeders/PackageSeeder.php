<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\CardType;
use App\Models\Collection;
use App\Models\Package;
use App\Models\Product;
use App\Models\Sticker;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $data = [];
        for ($i = 0; $i < 500; $i++) {
            $data[] = [
               'user_id' => User::inRandomOrder()->first()->id,
               'product_id' => Product::inRandomOrder()->first()->id,
            ];
        }
        foreach (array_chunk($data, 1000) as $chunk) {
            Package::insert($chunk);
        }
    }
}
