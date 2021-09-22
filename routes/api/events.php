<?php

use App\Http\Controllers\Event\ActsController;
use App\Http\Controllers\Event\AwardsController;
use App\Http\Controllers\Event\EditionsSeasonsController;
use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\Event\TrailerController;

Route::group(
    ['namespace' => 'Events'], static function (): void {
    Route::post('/', [EventController::class, 'index']);
    Route::post('/create', [EventController::class, 'create']);
    Route::get('/{id}', [EventController::class, 'show'])->where(['id' => '[0-9]+']);
    Route::post('/{id}/update', [EventController::class, 'update'])->where(['id' => '[0-9]+']);
    Route::get('/{id}/trailers', [EventController::class, 'trailers'])->where(['id' => '[0-9]+']);
    Route::post('/add_to_bookmark', [EventController::class, 'addToBookmark'])->where(['id' => '[0-9]+']);
    Route::post('/search', [EventController::class, 'search']);
    Route::post('/{id}/applaud', [EventController::class, 'applaud'])->where(['id' => '[0-9]+']);
    Route::delete('/{id}/applaud', [EventController::class, 'deleteApplaud'])->where(['id' => '[0-9]+']);

    Route::group(
        ['prefix' => 'persons'], static function (): void {
        Route::post('attach', [EventController::class, 'attachPersons']);
        Route::post('detach', [EventController::class, 'detachPerson']);
    }
    );

    Route::group(
        ['prefix' => 'awards'], static function (): void {
        Route::get('/', [AwardsController::class, 'index']);
        Route::post('/create', [AwardsController::class, 'create']);
        Route::delete('/{id}', [AwardsController::class, 'delete'])->where(['id' => '[0-9]+']);
    }
    );

    Route::group(
        ['prefix' => 'venues'], static function (): void {
        Route::post('attach', [EventController::class, 'attachVenue']);
        Route::post('detach', [EventController::class, 'detachVenue']);
    }
    );

    Route::group(
        ['prefix' => 'acts'], static function (): void {
        Route::post('/attach', [ActsController::class, 'attachAct']);
        Route::post('/{id}/edit', [ActsController::class, 'updateAct'])->where(['id' => '[0-9]+']);
        Route::delete('/{id}', [ActsController::class, 'deleteAct'])->where(['id' => '[0-9]+']);
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

    Route::group(
        ['prefix' => 'media'], static function (): void {
        Route::post('attach_image', [EventController::class, 'attachMediaImage']);
        Route::post('attach_video', [EventController::class, 'attachMediaVideo']);
        Route::delete('detach', [EventController::class, 'detachMediaItem']);
        Route::get('images', [EventController::class, 'getImages']);
        Route::get('posters', [EventController::class, 'getPosters']);
        Route::get('videos', [EventController::class, 'getVideos']);
    }
    );

    Route::group(
        ['prefix' => 'editions'], static function (): void {
        Route::post('attach', [EditionsSeasonsController::class, 'attachEdition']);
        Route::post('detach', [EditionsSeasonsController::class, 'detachEdition']);
        Route::post('search', [EditionsSeasonsController::class, 'searchByEditions']);
    }
    );

    Route::group(
        ['prefix' => 'seasons'], static function (): void {
        Route::post('attach', [EditionsSeasonsController::class, 'attachSeasons']);
        Route::post('/{id}/update', [EditionsSeasonsController::class, 'editSeason'])->where(['id' => '[0-9]+']);
        Route::delete('/{id}/delete', [EditionsSeasonsController::class, 'deleteSeason'])->where(['id' => '[0-9]+']);
    }
    );
}
);
