<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Mockery\Exception;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($request->filled('sort') && in_array($request->input('sort'), ['name', 'email', 'id'])) {
            $query->orderBy($request->input('sort'), $request->input('direction') === 'desc' ? 'desc' : 'asc');
        }

        $users = $query->paginate(10)->withQueryString();

        $data = [];
        foreach ($users as $key=>$user){
            $users[$key] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->isNotEmpty()? $user->getRoleNames()[0]: '---',
                'created_at' => Carbon::parse($user->created_at)->format('d/m/Y')
            ];
        }
        
        $roles = Role::where('name', '!=', 'guest')->get();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only('search', 'sort', 'direction'),
            'roles' => $roles
        ]);
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'guest')->get();

        return Inertia::render('Admin/Users/Create', [
            'roles' => $roles,
        ]);
    }

    public function store(UserRequest $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
            ]);
            
            $user->assignRole($data['role']);
            DB::commit();
            event(new Registered($user));
            return redirect()->route('admin.users.show',['user'=> $user->id])->with('success', 'Usuario creado correctamente');
        }catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al crear usuario: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            
            return back()->withErrors(['error' => 'Hubo un problema al crear el usuario'])->withInput();
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
        $roles = Role::where('name', '!=', 'guest')->get();
        
        $user['role'] = $user->roles()->first()->name;

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);
            
            $user->syncRoles([$data['role']]);
            
            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente');
        }catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al actualizar usuario: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Hubo un problema al actualizar el usuario']);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', __('user_delete_success'));
        }catch (\Throwable $e){
            Log::error(__('user_delete_error') . $e->getMessage());
            return back()->withErrors(['error' => __('user_delete_error')]);
        }
    }
}

