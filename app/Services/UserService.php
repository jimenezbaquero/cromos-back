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
        $query = User::with('roles');

        if(!empty($filters)) {
           $query = FiltersHelper::applyTableFilter($query, $filters);
        }
        $page = $filters['page']['page']?? $filters['page']?? 1;
        $perPage = $filters['page']['perPage']?? $filters['perPage']?? 10;
        return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }

    public function createUser(array $data)
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
            Log::error('Error al crear usuario: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'input' => $data,
            ]);
            throw $e;
        }
    }

    public function updateUser(User $user, array $data)
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
            Log::error('Error al actualizar usuario: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'input' => $data,
            ]);
            throw $e;
        }
    }

    public function deleteUser(User $user): bool
    {
        try {
            $user->delete();
            return true;
        } catch (\Throwable $e) {
            Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return false;
        }
    }
}
