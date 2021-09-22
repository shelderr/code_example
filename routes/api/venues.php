<?php

use App\Http\Controllers\Venue\VenueController;

Route::group(
    ['namespace' => 'Venue'], static function (): void {
    Route::get('/', [VenueController::class, 'index']);
    Route::get('/{id}/show', [VenueController::class, 'show'])->where(['id' => '[0-9]+']);
    Route::post('/create', [VenueController::class, 'create']);
    Route::post('/{id}/update', [VenueController::class, 'update'])->where(['id' => '[0-9]+']);
    Route::post('add_to_bookmarks', [VenueController::class, 'addToBookmarks']);

    Route::group(['prefix' => 'media'], static function(): void {
        Route::post('attach_image', [VenueController::class, 'attachMediaImage']);
        Route::delete('detach', [VenueController::class, 'detachMediaItem']);
    });
}
);