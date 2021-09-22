<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Management\CollectiveController;
use App\Http\Controllers\Admin\Management\EventController;
use App\Http\Controllers\Admin\Management\FeedbackController;
use App\Http\Controllers\Admin\Management\PersonController;
use App\Http\Controllers\Admin\Management\UsersController;
use App\Http\Controllers\Admin\Management\VenueController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\Management\TrailerController;
use App\Http\Controllers\User\Auth\SocialAuthController;
use App\Services\Base\BaseAppGuards;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(
    ['namespace' => 'Auth'],
    static function (): void {
        Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
        Route::get('/', [AuthController::class, 'getAuth'])->middleware(['auth:admin']);
        Route::get('/token/refresh', [AuthController::class, 'refreshToken']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::put('/change_password', [ForgotPasswordController::class, 'changePassword']);
        Route::post('confirm_email', [AuthController::class, 'confirmEmail']);

        Route::group(
            ['prefix' => 'google'], static function (): void {
            Route::post('/login', [SocialAuthController::class, 'login']);
        }
        );

        Route::group(
            ['prefix' => 'forgot_password', 'middleware' => 'guest'], static function (): void {
            Route::post('/token', [ForgotPasswordController::class, 'checkToken']);
            Route::post('/email', [ForgotPasswordController::class, 'sendLinkEmail']);
            Route::put('/reset', [ForgotPasswordController::class, 'resetPassword']);
        }
        );

        Route::group(
            ['prefix' => 'settings'], static function (): void {
            Route::post('/change_phone', [SettingsController::class, 'changePhone']);
            Route::post('/change_name', [SettingsController::class, 'changeName']);
        }
        );
    }
);

Route::group(
    [
        'middleware' => [
            'active.user',
            'jwt.token.refresh',
            'auth:' . BaseAppGuards::ADMIN,
        ],
    ], static function (): void {
    Route::group(
        ['prefix' => 'management'], static function (): void {
        Route::group(
            ['prefix' => 'events'], static function (): void {
            Route::get('/', [EventController::class, 'index']);
            Route::post('/create', [EventController::class, 'create']);
            Route::post('/{id}/update', [EventController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::post('/{id}/block_switch', [EventController::class, 'blockSwitch'])->where(['id' => '[0-9]+']);
            Route::delete('/{id}', [EventController::class, 'delete'])->where(['id' => '[0-9]+']);

            Route::group(
                ['prefix' => 'trailers'], static function (): void {
                Route::post('/', [TrailerController::class, 'create']);
                Route::put('/{id}', [TrailerController::class, 'update'])->where(['id' => '[0-9]+']);
                Route::delete('/{id}', [TrailerController::class, 'delete'])->where(['id' => '[0-9]+']);
            }
            );
        }
        );

        Route::group(
            ['prefix' => 'users'], static function (): void {
            Route::get('/', [UsersController::class, 'index']);
            Route::get('/delete_requests', [UsersController::class, 'profileDeleteRequests']);
            Route::put('/{id}/block_switch', [UsersController::class, 'blockSwitch'])->where(['id' => '[0-9]+']);
            Route::delete('/{id}/delete', [UsersController::class, 'delete'])->where(['id' => '[0-9]+']);
            Route::put('/{id}/delete/reject', [UsersController::class, 'rejectDeleteRequest'])->where(['id' => '[0-9]+']);
            Route::get('/{id}/activity', [UsersController::class, 'activity'])->where(['id' => '[0-9]+']);

            Route::group(['prefix' => 'verifications'], static function() {
                Route::get('/',  [UsersController::class, 'verificationRequests']);
                Route::post('/verify', [UsersController::class, 'verifyUserLink'])->where(['id' => '[0-9]+']);
            });
        }
        );

        Route::group(
            ['prefix' => 'persons'], static function (): void {
            Route::get('/', [PersonController::class, 'index']);
            Route::post('/create', [PersonController::class, 'create']);
            Route::post('/{id}/update', [PersonController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::delete('{id}/delete', [PersonController::class, 'delete'])->where(['id' => '[0-9]+']);
            Route::post('/{id}/block_switch', [PersonController::class, 'blockSwitch']);
        }
        );

        Route::group(
            ['prefix' => 'collectives'], static function (): void {
            Route::get('/', [CollectiveController::class, 'index']);
            Route::post('/{id}/block_switch', [CollectiveController::class, 'blockSwitch']);
            Route::delete('/{id}', [CollectiveController::class, 'delete'])->where(['id' => '[0-9]+']);
        }
        );

        Route::group(
            ['prefix' => 'feedbacks'], static function (): void {
            Route::get('/', [FeedbackController::class, 'index']);
        }
        );


        Route::group(['prefix' => 'venues'], static function(): void {
            Route::get('/', [VenueController::class, 'index']);
            Route::post('/{id}/block_switch', [VenueController::class, 'blockSwitch']);
            Route::delete('/{id}', [VenueController::class, 'delete'])->where(['id' => '[0-9]+']);
        });
        /*Route::group(
            ['prefix' => 'admins'], static function (): void {
            Route::get('/', [AdminsController::class, 'index']);
            Route::get('/{id}/show', [AdminsController::class, 'show'])->where(['id' => '[0-9]+']);
            Route::post('/invite', [AdminsController::class, 'invite']);
            Route::delete('/{id}/delete', [AdminsController::class, 'delete'])->where(['id' => '[0-9]+']);
            Route::put('/{id}/block-switch', [AdminsController::class, 'blockSwitch'])->where(['id' => '[0-9]+']);
            Route::get('/permissions', [AdminsController::class, 'permissions']);
            Route::post('/{id}/edit', [AdminsController::class, 'edit'])->where(['id' => '[0-9]+']);
        }
        );*/
    }
    );
}
);


