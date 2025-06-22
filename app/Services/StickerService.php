<?php

namespace App\Services;

use App\Models\Card;
use App\Models\Package;
use App\Models\Sticker;
use App\Models\User;

class StickerService
{
    public function generateSticker(User $user, Package $package, Card $card){
        $sticker = Sticker::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'card_id' => $card->id
        ]);
        
        return $sticker;
    }
}
