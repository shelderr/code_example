<?php

use App\Http\Controllers\User\Auth\AuthController;
use App\Http\Controllers\User\Auth\EmailConfirmationController;
use App\Http\Controllers\User\Auth\ForgotPasswordController;
use App\Http\Controllers\User\Auth\SocialAuthController;
use App\Http\Controllers\User\BookmarkController;
use App\Http\Controllers\User\NewslettersController;
use App\Http\Controllers\User\PersonalityController;
use App\Http\Controllers\User\UserController;
use App\Services\Base\BaseAppGuards;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\SettingsController;

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
        Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
        Route::put('/email_confirmation', [EmailConfirmationController::class, 'confirmEmail']);
        Route::put('/email_confirmation/resend', [AuthController::class, 'resentEmailConfirmationToken'])
            ->middleware('throttle.request:10,email_confirmation');

        Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
        Route::get('/token/refresh', [AuthController::class, 'refreshToken']);

        Route::group(
            ['prefix' => 'password'],
            static function (): void {
                Route::post('/email', [ForgotPasswordController::class, 'sendResetTokenEmail']);
                Route::post('/token', [ForgotPasswordController::class, 'checkToken']);
                Route::put('/', [ForgotPasswordController::class, 'resetPassword']);
            }
        );

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::group(
            ['prefix' => 'social'], static function (): void {
            Route::post('/google/login', [SocialAuthController::class, 'googleLogin'])->middleware('guest');
            Route::post('/facebook/login', [SocialAuthController::class, 'facebookLogin'])->middleware('guest');
        }
        );
    }
);

Route::group(
    ['prefix' => 'newsletters'],
    static function (): void {
        Route::post('/subscribe', [NewslettersController::class, 'mailchimpSubscribe'])
            ->middleware('throttle.request:3');
    }
);

Route::group(
    [
        'middleware' => [
            'jwt.token.refresh',
            'active.user',
            'auth:' . BaseAppGuards::USER,
        ],
    ],
    static function (): void {
        Route::get('/', [UserController::class, 'index']);

        Route::group(
            ['prefix' => 'settings'],
            static function (): void {
                Route::delete('/delete_account', [SettingsController::class, 'deleteAccount']);
                Route::post('/edit_data', [SettingsController::class, 'selfEdit']);
                Route::put('/change_password', [SettingsController::class, 'changePassword']);
                Route::post('/upload_photo', [SettingsController::class, 'changePhoto']);
                Route::put('/change_username', [SettingsController::class, 'changeUsername']);

                Route::group(
                    ['prefix' => 'subscribes'],
                    static function (): void {
                        Route::post('newsletters_switch', [NewslettersController::class, 'newsletterSubscribeSwitch']);
                        Route::post('system_notification_switch', [NewslettersController::class, 'systemNewsletterSubscribeSwitch']);
                    }
                );

                Route::group(
                    ['prefix' => 'personality'],
                    function (): void {
                        Route::post('/request_link', [PersonalityController::class, 'sendVerificationRequest']);
                    }
                );
            }
        );

        Route::group(
            ['prefix' => 'bookmarks'], static function (): void {
            Route::delete('/delete', [BookmarkController::class, 'deleteBookmark']);

            Route::group(
                ['prefix' => 'folders'], static function (): void {
                Route::get('/', [BookmarkController::class, 'index']);
                Route::post('/search', [BookmarkController::class, 'searchFolder']);
                Route::post('/create', [BookmarkController::class, 'createFolder']);
                Route::get('/{id}/show', [BookmarkController::class, 'showFolder'])->where(['id' => '[0-9]+']);
                Route::put('/{id}/edit', [BookmarkController::class, 'updateFolder'])->where(['id' => '[0-9]+']);
                Route::delete('/{id}/delete', [BookmarkController::class, 'deleteFolder'])->where(['id' => '[0-9]+']);
            }
            );
        }
        );
    }
);