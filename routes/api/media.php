<?php

use App\Http\Controllers\Event\ActsController;
use App\Http\Controllers\Event\EditionsSeasonsController;
use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\Event\TrailerController;
use App\Http\Controllers\Media\MediaController;

Route::group(
    ['namespace' => 'Media'], static function (): void {
    Route::get('/', [MediaController::class, 'index']);
}
);
