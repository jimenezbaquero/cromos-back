<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\CardType;
use App\Models\Collection;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        foreach (Collection::all() as $collection) {
            for ($number = 1; $number <= $collection->total_cards; $number++) {
                $cardType = CardType::inRandomOrder()->first();
                $urlPhoto = $cardType->name == 'Horizontal' ? Storage::url('card_photos/cromo_horizontal.png') : Storage::url('card_photos/cromo_vertical.png');
                
                $data[] =[
                    'number' => $number,
                    'collection_id' => $collection->id,
                    'card_type_id' => $cardType->id,
                    'url_photo' => $urlPhoto,
                    'probability' => $this->chooseProbability(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }
        foreach (array_chunk($data, 1000) as $chunk) {
            Card::insert($chunk);
        }
    }
    
    public function chooseProbability() {
        $rand = random_int(0, 100);
        if($rand > 90) {
            return 10;
        }
        if($rand > 66) {
            return 25;
        }
        if($rand > 33) {
            return 50;
        }
        return 100;
    }
}
