<?php

use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\CardTypeController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $user = Auth::user();
    if (is_null($user)) {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    } else {
        return redirect()->route('dashboard');
    }
})->name('home');

Route::post('/language', function (Illuminate\Http\Request $request) {
    $lang = $request->input('locale');
    
    if (in_array($lang, [
        'en',
        'es',
        'fr'
    ])) {
        session(['locale' => $lang]);
        app()->setLocale($lang);
    }
    return response()->json(['status' => 'ok']);
});

Route::get('/auth/{provider}', [
    SocialLoginController::class,
    'redirect'
])->name('social.redirect');
Route::get('/auth/{provider}/callback', [
    SocialLoginController::class,
    'callback'
])->name('social.callback');

Route::group([
    'middleware' => [
        'auth',
        'verified',
    ],
], function () {
    Route::get('/dashboard', [
        DashboardController::class,
        'redirect'
    ])->name('dashboard');
    Route::get('/profile', [
        ProfileController::class,
        'edit'
    ])->name('profile.edit');
    Route::patch('/profile', [
        ProfileController::class,
        'update'
    ])->name('profile.update');
    Route::delete('/profile', [
        ProfileController::class,
        'destroy'
    ])->name('profile.destroy');
});

Route::group([
    'middleware' => [
        'auth',
        'verified',
        'role:admin'
    ],
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {
    Route::resource('users', UserController::class);
    Route::resource('publishers', PublisherController::class)->except(['show','destroy']);
    
    Route::resource('collections', CollectionController::class);
    
    Route::resource('cards', CardController::class);
    Route::group([
        'prefix' => 'cards',
    ],function() {
        Route::get('/showCardsCollection/{collection}', [CardController::class, 'showCardsCollection'])->name('cards.showCardsCollection');
    });
    Route::resource('cardtypes', CardTypeController::class);
});

Route::middleware('web')->group(function () {
    require __DIR__ . '/auth.php';
});
