<?php

namespace App\Services;

use App\Helper\FiltersHelper;
use App\Models\Collection;
use Barryvdh\Snappy\Facades\SnappyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

class CollectionService
{
    public function getDataWithFilters($filters) {
        $query = Collection::query();
        
        if (!empty($filters)) {
            $query = FiltersHelper::applyTableFilter($query, $filters);
        }
        $page = $filters['page']['page'] ?? $filters['page'] ?? 1;
        $perPage = $filters['page']['perPage'] ?? $filters['perPage'] ?? 10;
        return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }
    
    public function store(array $data) {
        try {
            //TODO tratar la imagen de la coleccion
            $collection = Collection::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'year' => $data['year'],
                'publisher_id' => $data['publisher_id']
            ]);
            
            return $collection;
        } catch (\Throwable $e) {
            Log::error('Error al crear colección: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'input' => $data,
            ]);
            throw $e;
        }
    }
    
    public function update(Collection $collection, array $data) {
        DB::beginTransaction();
        try {
            $collection->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'year' => $data['year'],
            ]);
            
            $collection->publisher()->attach($data['publisher_id']);
            
            DB::commit();
            
            return $collection;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al actualizar colección: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'input' => $data,
            ]);
            throw $e;
        }
    }
    
    public function destroy(Collection $collection): bool {
        try {
            $collection->delete();
            return true;
        } catch (\Throwable $e) {
            Log::error('Error al eliminar colección: ' . $e->getMessage());
            return false;
        }
    }
    
    public function getCollectionsToClient(Request $request) {
        $query = Auth::user()->collections();
        
        if (!empty($filters)) {
            $query = FiltersHelper::applyTableFilter($query, $filters);
        }
        $page = $filters['page']['page'] ?? $filters['page'] ?? 1;
        $perPage = $filters['page']['perPage'] ?? $filters['perPage'] ?? 10;
        return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }
    
    public function generatePage(Collection $collection, int $page) {
        $initialCard = 9 * ($page - 1) + 1;
        if($initialCard > $collection->total_card){
            return [
                'exists' => false,
                'message' => 'La página solicitada no existe.'
            ];
        }
        $actualcard = $initialCard;
        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 3; $col++) {
                $image = ['number' => $actualcard];
                $card = $collection->cards->where('number', $actualcard)->first();
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
        $html = view('album', compact('images'))->render();
        
        $image = Browsershot::html($html)
            ->windowSize(400, 800)
            ->waitUntilNetworkIdle()
            ->screenshot();
        
        return [
            'exists' => true,
            'page' => 'data:image/png;base64,' . base64_encode($image)];
    }
}
