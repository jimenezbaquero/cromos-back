<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\TestController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('collections',[CollectionController::class, 'getCollections']);
    Route::get('subscribedCollections',[CollectionController::class, 'getSubscribedCollections']);
    Route::get('cards/{collection}',[CollectionController::class, 'getSCardsCollection']);
    
});
