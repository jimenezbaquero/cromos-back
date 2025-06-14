<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $user['balance'] = $user->balance ?? 0;
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => optional($request->session()->get('errors'))->get('error')[0] ?? null,
            ],
            'locale' => $request->session()->get('locale'),
        ];
    }
}
