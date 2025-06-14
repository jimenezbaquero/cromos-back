<?php

use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\CardTypeController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Client\CollectionController as ClientCollectionController;
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
    Route::group([
        'prefix' => 'users',
    ],function() {
        Route::post('getData', [UserController::class,'getData'])->name('users.getData');
    });
    Route::resource('publishers', PublisherController::class)->except(['show','destroy']);
    Route::group([
        'prefix' => 'publishers',
    ],function() {
        Route::post('getData', [PublisherController::class,'getData'])->name('publishers.getData');
    });

    Route::resource('collections', CollectionController::class);
    Route::group([
        'prefix' => 'collections',
    ],function() {
        Route::post('getData', [CollectionController::class,'getData'])->name('collections.getData');
        Route::get('getPage/{collection}/{page}', [CollectionController::class,'getPage'])->name('collections.getPage');
    });

    Route::resource('cards', CardController::class);
    Route::group([
        'prefix' => 'cards',
    ],function() {
        Route::get('/showCardsCollection/{collection}', [CardController::class, 'showCardsCollection'])->name('cards.showCardsCollection');
        Route::post('getData', [CardController::class,'getData'])->name('cards.getData');
    });

    Route::resource('cardtypes', CardTypeController::class);
});

Route::group([
    'middleware' => [
        'auth',
        'verified',
        'role:client'
    ],
    'prefix' => 'client',
    'as' => 'client.'
], function () {
    Route::resource('collections', ClientCollectionController::class)->only(['index', 'show']);
    Route::group([
        'prefix' => 'collections',
    ], function () {
        Route::get('/collections/subscribed', [
            CollectionController::class,
            'getSubscribedCollections'
        ])->name('collections.getSubscribedCollections');
    });
});

Route::middleware('web')->group(function () {
    require __DIR__ . '/auth.php';
});
