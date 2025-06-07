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
        $combinations = [];
        $data = [];
        for ($i = 1; $i <= 5000; $i++) {
            $collection = Collection::inRandomOrder()->first();
            $cardType = CardType::inRandomOrder()->first();
            $combination = $this->chooseCombination($combinations, $collection);
            $urlPhoto = $cardType->name == 'Horizontal' ? Storage::url('card_photos/cromo_horizontal.png') : Storage::url('card_photos/cromo_vertical.png');
            $data[] =[
                'number' => $combination[0],
                'collection_id' => $collection->id,
                'card_type_id' => $cardType->id,
                'url_photo' => $urlPhoto,
                'probability' => $this->chooseProbability(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $combinations[] = $combination;
        }
        Card::insert($data);
    }
    
    public function chooseCombination(array $combinations, Collection $collection) {
        do {
            $combination = [
                random_int(0, 400),
                $collection->id
            ];
        } while (in_array($combination, $combinations));
        return $combination;
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
