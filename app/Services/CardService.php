<?php

namespace App\Services;

use App\Helper\FiltersHelper;
use App\Models\Card;
use App\Models\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CardService
{
    public function getDataWithFilters($filters)
    {
        $query = Card::query();

        if(!empty($filters)) {
           $query = FiltersHelper::applyTableFilter($query, $filters);
        }
        $page = $filters['page']['page']?? $filters['page']?? 1;
        $perPage = $filters['page']['perPage']?? $filters['perPage']?? 5;
        return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }

    public function getDataByCollection($filters,Collection $collection)
    {
        $query = Card::where('collection_id',$collection->id);

        if(!empty($filters)) {
            $query = FiltersHelper::applyTableFilter($query, $filters);
        }
        $page = $filters['page']['page']?? $filters['page']?? 1;
        $perPage = $filters['page']['perPage']?? $filters['perPage']?? 5;
        return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }

    public function createCollection(array $data)
    {
        try {
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

    public function updateCollection(Collection $collection, array $data)
    {
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

    public function deleteCollection(Collection $collection): bool
    {
        try {
            $collection->delete();
            return true;
        } catch (\Throwable $e) {
            Log::error('Error al eliminar colección: ' . $e->getMessage());
            return false;
        }
    }
}
