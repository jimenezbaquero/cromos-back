<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\CardType;
use App\Models\Collection;
use App\Models\Package;
use App\Models\Sticker;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class StickerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $stickers = [];
        foreach (Package::all() as $package) {
            $collection = Collection::inRandomOrder()->first();
            $user = User::role('client')->inRandomOrder()->first();
            $package = Package::inRandomOrder()->first();
            $stickers[] = [
                'card_id' => $collection->cards()->inRandomOrder()->first()->id,
                'user_id' => $user->id,
                'package_id' => $package->id,
            ];
        }
        foreach (array_chunk($stickers, 1000) as $chunk) {
            Sticker::insert($chunk);
        }
    }
}
