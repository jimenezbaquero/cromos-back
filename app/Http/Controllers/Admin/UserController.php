<?php

namespace App\Http\Controllers\Admin;

use App\Filters\UserFilter;
use App\Headers\UserHeader;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\RoleService;
use App\Services\UserService;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserController extends Controller
{
    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        $filters = UserFilter::getFilters();
        $headers = UserHeader::getHeaders();
        $users = $this->getDataWithFilters($request);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => $filters,
            'headers' => $headers,
            'funnels' => UserFilter::getFunnelOptions(),
        ]);
    }

    public function create()
    {
        $roles = $this->roleService->getRoles();

        return Inertia::render('Admin/Users/Create', [
            'roles' => $roles,
        ]);
    }

    public function store(UserRequest $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $this->userService->createUser($request->all());
            return redirect()->route('admin.users.index')->with('success', 'user_create_success');
        }catch (\Throwable $e) {
            Log::error(__('user_create_error').' - ' . $e->getMessage());
            return back()->withErrors(['error' => __('user_create_error')])->withInput();
        }
    }

    public function show(User $user)
    {
        $user['role'] = $user->roles->first()->name;;

        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user)
    {
        $roles = $this->roleService->getRoles();

        $user['role'] = $user->roles()->first()->name;

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        try {
            $this->userService->updateUser($user, $request->all());
            return redirect()->route('admin.users.index')->with('success', __('user_update_success'));
        }catch (\Throwable $e) {
            Log::error(__('user_update_error') .' - '. $e->getMessage());
            return back()->withErrors(['error' => __('user_update_error')]);
        }
    }

    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user);
            return redirect()->route('users.index')->with('success', __('user_delete_success'));
        }catch (\Throwable $e){
            Log::error(__('user_delete_error').' - '. $e->getMessage());
            return back()->withErrors(['error' => __('user_delete_error')]);
        }
    }

    public function getData(Request $request){
        $users  = $this->getDataWithFilters($request);
        return response()->json($users);
    }

    public function getDataWithFilters(Request $request){
        $users  = $this->userService->getDataWithFilters($request->all());
        $transforms = $users->getCollection()->map(function ($item) {
            return UserTransformer::transformToWebIndex($item);
        });

        $users->setCollection($transforms);
        return $users;
    }
}

