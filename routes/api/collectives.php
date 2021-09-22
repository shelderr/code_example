<?php

use App\Http\Controllers\Collective\CollectiveController;
use App\Http\Controllers\Collective\TrailerController;

Route::group(
    ['namespace' => 'Collective'], static function (): void {
    Route::post('/', [CollectiveController::class, 'index']);
    Route::get('/{id}/show', [CollectiveController::class, 'show'])->where(['id' => '[0-9]+']);
    Route::post('/create', [CollectiveController::class, 'create']);
    Route::post('/{id}/update', [CollectiveController::class, 'update'])->where(['id' => '[0-9]+']);
    Route::post('add_to_bookmarks', [CollectiveController::class, 'addToBookmarks']);

    Route::prefix('persons')->group(
        static function (): void {
            Route::post('/attach', [CollectiveController::class, 'attachPerson']);
            Route::put('/update', [CollectiveController::class, 'editAttachedPerson']);
            Route::delete('/delete', [CollectiveController::class, 'deletePerson']);
        }
    );

    Route::group(
        ['prefix' => 'media'], static function (): void {
        Route::post('attach_image', [CollectiveController::class, 'attachMediaImage']);
        Route::post('attach_video', [CollectiveController::class, 'attachMediaVideo']);
        Route::delete('detach', [CollectiveController::class, 'detachMediaItem']);
        Route::get('images', [CollectiveController::class, 'getImages']);
        Route::get('videos', [CollectiveController::class, 'getVideos']);
        Route::get('headshots', [CollectiveController::class, 'getHeadshots']);
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
}
);