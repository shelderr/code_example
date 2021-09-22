<?php

use App\Http\Controllers\Show\ShowController;

Route::group(
    ['namespace' => 'Shows'], static function (): void {
    Route::post('/create', [ShowController::class, 'create']);
    Route::post('/{id}/update', [ShowController::class, 'update'])->where(['id' => '[0-9]+']);
}
);
