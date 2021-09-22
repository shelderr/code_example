<?php

use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\General\CountryController;
use App\Http\Controllers\General\CriticsController;
use App\Http\Controllers\General\DetailsController;
use App\Http\Controllers\General\FeedbackController;
use App\Http\Controllers\General\GoogleMapsController;
use App\Http\Controllers\General\LanguageController;
use App\Http\Controllers\General\CategoriesController;
use App\Http\Controllers\General\LinksController;
use App\Http\Controllers\General\SearchController;

Route::group(
    ['namespace' => 'General'],
    static function (): void {
        Route::group(
            ['prefix' => 'countries'], static function (): void {
            Route::get('/', [CountryController::class, 'index']);
        }
        );

        Route::group(
            ['prefix' => 'languages'], static function (): void {
            Route::get('/', [LanguageController::class, 'index']);
        }
        );

        Route::group(
            ['prefix' => 'categories'], static function (): void {
            Route::get('/', [CategoriesController::class, 'index']);
        }
        );

        Route::group(
            ['prefix' => 'search'], static function (): void {
            Route::get('/', [SearchController::class, 'search']);
            Route::get('/global', [SearchController::class, 'elasticsearch']);
        }
        );

        Route::group(
            ['prefix' => 'googleMaps'], static function (): void {
            Route::get('/', [GoogleMapsController::class, 'searchLocation']);
            Route::get('/get_events_list', [EventController::class, 'getGoogleMapsEvents']);
        }
        );

        Route::group(
            ['prefix' => 'feedback'], static function (): void {
                Route::post('/send', [FeedbackController::class, 'sendFeedback']);
        }
        );

        //Details for events,shows,venues etc.
        Route::group(
            ['prefix' => 'details'], static function (): void {
            Route::post('/attach', [DetailsController::class, 'attach']);
            Route::post('/{id}/update', [DetailsController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::delete('/{id}/delete', [DetailsController::class, 'delete'])->where(['id' => '[0-9]+']);
        }
        );

        //Links for events,shows,venues etc.
        Route::group(
            ['prefix' => 'links'], static function (): void {
            Route::post('/attach', [LinksController::class, 'attach']);
            Route::delete('/{id}/delete', [LinksController::class, 'delete'])->where(['id' => '[0-9]+']);
        }
        );

        //Links for shows,collectives
        Route::group(
            ['prefix' => 'critics'], static function (): void {
            Route::post('/attach', [CriticsController::class, 'attach']);
            Route::delete('/{id}/delete', [CriticsController::class, 'delete'])->where(['id' => '[0-9]+']);
        }
        );
    }
);
