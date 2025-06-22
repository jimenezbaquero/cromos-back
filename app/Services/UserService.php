<?php

namespace App\Services;

use App\Helper\FiltersHelper;
use App\Models\Collection;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;

class UserService
{
    protected $packageService;
    public function __construct(PackageService $packageService) {
        $this->packageService = $packageService;
    }
    
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
    
    public function buy(User $user, Collection $collection, Product $product) {
        
        return DB::transaction(function () use ($user, $collection, $product) {
            $user->pay($product);
            
            $package = $this->packageService->generatePackage($user, $collection, $product);
            $imagePackage = $this->packageService->generateImage($package);
            
            return [
                'status'     => 'success',
                'message'    => 'Compra con éxito',
                'image' => $imagePackage,
            ];
        });
    }
}
