<?php

namespace App\Services;

use App\Helper\FiltersHelper;
use App\Helper\OptionHelper;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Event;

class UserService
{
    public function getUsersWithFilters($filters)
    {
        $query = User::with('roles');

        if(!empty($filters)) {
           $query = FiltersHelper::applyTableFilter($query, $filters);
        }
        return $query->paginate(10)->withQueryString();
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

    public function getHeaders() {
        return [
            'id' => [
                'label' => 'Id',
                'type' => 'text',
                'filterable' => false,
                'sortable' => true,
            ],
            'name' => [
                'label' => __('name'),
                'type' => 'text',
                'filterable' => true,
                'sortable' => true,
            ],
            'email' => [
                'label' => __('email'),
                'type' => 'text',
                'filterable' => true,
                'sortable' => true,
            ],
            'role' => [
                'label' => __('role'),
                'type' => 'text',
                'filterable' => false,
                'sortable' => true,
                'funnel' => true
            ],
            'created_at' => [
                'label' => __('created_at'),
                'type' => 'date',
                'filterable' => true,
                'sortable' => true,
            ],
        ];
    }

    public function getFilters() {
        return [
            'id' => [
                'field' => 'users.id',
                'value' => '',
                'sort' => '',
            ],
            'name' => [
                'field' => 'users.name',
                'value' => '',
                'sort' => '',
            ],
            'email' => [
                'field' => 'users.email',
                'value' => '',
                'sort' => '',
            ],
            'role' => [
                'field' => 'roles.id',
                'value' => '',
                'sort' => '',
                'funnel' => []
            ],
            'created_at' => [
                'field' => 'users.created_at',
                'value' => '',
                'sort' => '',
            ],
            'search' => [
                'field' => 'users.name|users.email',
                'value' => '',
                'sort' => '',
            ],

        ];
    }

    public function getFunnelOptions() {
        return [
            "role" => OptionHelper::getRoleOptions(),
        ];
    }
}
