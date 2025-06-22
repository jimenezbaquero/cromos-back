<?php

namespace App\Http\Controllers\Client;

use App\Filters\UserFilter;
use App\Headers\UserHeader;
use App\Helper\TransformHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Collection;
use App\Models\Product;
use App\Models\User;
use App\Services\RoleService;
use App\Services\UserService;
use App\Transformers\CardTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function buy(Collection $collection,Product $product){
        try {
            $user = Auth::user();
            $response = $this->userService->buy($user, $collection, $product);
            return response()->json($response, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo completar la compra.',
                'details' => $e->getMessage(), // o un mensaje genérico
            ], 400);
        }
    }
    
}

