<?php

namespace App\Services;

use App\Models\Card;
use App\Models\Collection;
use App\Models\Package;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Spatie\Browsershot\Browsershot;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Event;

class PackageService
{
    protected StickerService $stickerService;
    
    public function __construct(StickerService $stickerService){
        $this->stickerService = $stickerService;
    }
    public function generatePackage(User $user, Collection $collection, Product $product){
        $package = Package::create([
            'collection_id' => $collection->id,
            'product_id' => $product->id,
            'user_id' => $user->id
        ]);
        
        //TODO logica de probabilidades de obtención de cromos
        $cards = $collection->cards()->inRandomOrder()->take(6)->get();
        
        foreach($cards as $card){
            $this->stickerService->generateSticker($user,$package,$card);
        }
        
        return $package;
    }
    
    public function generateImage(Package $package){
        $index = 1;
        foreach ($package->stickers as $sticker){
            $cards[$index++] = $sticker->card;
        }
        
        $actualcard = 1;
        for ($row = 0; $row < 2; $row++) {
            for ($col = 0; $col < 3; $col++) {
                $image = ['number' => $actualcard];
                $card = $cards[$actualcard];
                if ($card) {
                    if(!is_null($card->url_photo)){
                        $url =public_path($card->url_photo);
                        $image['url'] = $url;
                    } else {
                        $url = 'no tiene url';
                    }
                } else {
                    $url = 'no existe el cromo';
                }
                $image['url'] = $url;
                $images[$row][$col] = $image;
                $actualcard++;
            }
        }
        
        $html = view('package', compact('images',))->render();
        
        $image = Browsershot::html($html)
            ->windowSize(400, 600)
            ->waitUntilNetworkIdle()
            ->screenshot();
        
        return 'data:image/png;base64,' . base64_encode($image);
    }
}
