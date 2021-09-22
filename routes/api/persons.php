<?php

use App\Http\Controllers\Person\PersonController;
use App\Http\Controllers\Person\RolesController;
use App\Http\Controllers\Person\TrailerController;

Route::group(
    ['namespace' => 'Person'], static function (): void {
    Route::post('/', [PersonController::class, 'index']);
    Route::get('/search', [PersonController::class, 'search']);
    Route::post('/create', [PersonController::class, 'create']);
    Route::post('/{id}/update', [PersonController::class, 'update'])->where(['id' => '[0-9]+']);
    Route::get('/{id}/show', [PersonController::class, 'show'])->where(['id' => '[0-9]+']);
    Route::post('/add_to_bookmark', [PersonController::class, 'addToBookmark'])->where(['id' => '[0-9]+']);

    Route::group(
        ['prefix' => 'roles'], static function (): void {
            Route::get('/', [RolesController::class, 'index']);
            Route::get('/search', [RolesController::class, 'search']);
    }
    );

    Route::group(
        ['prefix' => 'trailers'],
        static function (): void {
            Route::post('/', [TrailerController::class, 'create']);
            Route::put('/{id}', [TrailerController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::delete('/{id}', [TrailerController::class, 'delete'])->where(['id' => '[0-9]+']);
        }
    );


    Route::group(['prefix' => 'media'], static function(): void {
        Route::post('attach_image', [PersonController::class, 'attachMediaImage']);
        Route::post('attach_video', [PersonController::class, 'attachMediaVideo']);
        Route::delete('detach', [PersonController::class, 'detachMediaItem']);
        Route::get('images', [PersonController::class, 'getImages']);
        Route::get('videos', [PersonController::class, 'getVideos']);
        Route::get('headshots', [PersonController::class, 'getHeadshots']);
    });
}
);