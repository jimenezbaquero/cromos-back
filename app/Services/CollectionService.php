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
        $user = Auth::user();
        return $user->collections;
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
        $images = [];
        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 3; $col++) {
                $card = $collection->cards->where('cards.number', $actualcard)->first();
                if ($card) {
                    // Obtener el contenido de la imagen
                    $imagePath = public_path('storage/' . $card->url_photo);
                    if (file_exists($imagePath)) {
                        $imageData = base64_encode(file_get_contents($imagePath));
                        $imageType = mime_content_type($imagePath);
                        $images[$row][$col] = 'data:' . $imageType . ';base64,' . $imageData;
                    } else {
                        $images[$row][$col] = null;
                    }
                } else {
                    $images[$row][$col] = null;
                }
                $actualcard++;
            }
        }
        $html = view('album', compact('images'))->render();

        $binary = SnappyImage::getOutputFromHtml($html, [
            'width'  => 800,
            'height' => 600,
        ]);
        return [
            'exists' => true,
            'page' => 'data:image/jpeg;base64,' . base64_encode($binary)];
    }
}
