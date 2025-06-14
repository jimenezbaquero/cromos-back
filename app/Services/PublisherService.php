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
        $page = $filters['page']['page']?? $filters['page']?? 1;
        $perPage = $filters['page']['perPage']?? $filters['perPage']?? 10;
        return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }

    public function store(array $data)
    {
        try {
            $publisher = Publisher::create([
                'name' => $data['name'],
            ]);

            return $publisher;
        } catch (\Throwable $e) {
            Log::error(__('publisher_create_error') . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'input' => $data,
            ]);
            throw $e;
        }
    }

    public function update(Publisher $publisher, array $data)
    {
        try {
            $publisher->update([
                'name' => $data['name'],
            ]);

            return $publisher;
        } catch (\Throwable $e) {
            Log::error(__('publisher_update_error') . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'input' => $data,
            ]);
            throw $e;
        }
    }

    public function destroy(Publisher $publisher): bool
    {
        try {
            $publisher->delete();
            return true;
        } catch (\Throwable $e) {
            Log::error(__('publisher_delete_error') . $e->getMessage());
            return false;
        }
    }
}
