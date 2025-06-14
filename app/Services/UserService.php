<?php

namespace App\Services;

use App\Helper\FiltersHelper;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;

class UserService
{
    public function getDataWithFilters($filters)
    {
        $query = User::with(['roles','wallet']);

        if(!empty($filters)) {
           $query = FiltersHelper::applyTableFilter($query, $filters);
        }
        $page = $filters['page']['page']?? $filters['page']?? 1;
        $perPage = $filters['page']['perPage']?? $filters['perPage']?? 10;
        return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
            ]);

            if(!isset($data['role'])){
                $data['role'] = 'client';
            }

            $user->assignRole($data['role']);

            DB::commit();

            event(new Registered($user));

            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error(__('user_create_error') . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'input' => $data,
            ]);
            throw $e;
        }
    }

    public function update(User $user, array $data)
    {
        DB::beginTransaction();
        try {
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            $user->syncRoles([$data['role']]);

            DB::commit();

            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error(__('user_update_error') . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'input' => $data,
            ]);
            throw $e;
        }
    }

    public function destroy(User $user): bool
    {
        try {
            $user->delete();
            return true;
        } catch (\Throwable $e) {
            Log::error(__('user_delete_error') . $e->getMessage());
            return false;
        }
    }
}
