<?php

namespace App\Services;

use App\Helper\FiltersHelper;
use App\Models\Publisher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;

class PublisherService
{
    public function getDataWithFilters($filters)
    {
        $query = Publisher::with('collections');

        if(!empty($filters)) {
           $query = FiltersHelper::applyTableFilter($query, $filters);
        }
        return $query->paginate(10)->withQueryString();
    }

    public function createPublisher(array $data)
    {
        try {
            $publisher = Publisher::create([
                'name' => $data['name'],
            ]);
            
            return $publisher;
        } catch (\Throwable $e) {
            Log::error('Error al crear editorial : ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'input' => $data,
            ]);
            throw $e;
        }
    }

    public function updatePublisher(Publisher $publisher, array $data)
    {
        try {
            $publisher->update([
                'name' => $data['name'],
            ]);

            return $publisher;
        } catch (\Throwable $e) {
            Log::error('Error al actualizar editorial: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'input' => $data,
            ]);
            throw $e;
        }
    }

    public function deletePublisher(Publisher $publisher): bool
    {
        try {
            $publisher->delete();
            return true;
        } catch (\Throwable $e) {
            Log::error('Error al eliminar editorial: ' . $e->getMessage());
            return false;
        }
    }
}
