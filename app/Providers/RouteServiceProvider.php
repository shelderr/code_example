<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    private string $basePath;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->basePath = base_path('routes/api/');
    }

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(function () {

                    Route::prefix('/')
                        ->group($this->basePath . 'api.php');

                    Route::prefix('user')
                        ->middleware('api')
                        ->namespace($this->namespace . '\\User')
                        ->group($this->basePath . 'user.php');

                    Route::prefix('admin')
                        ->middleware('api')
                        ->namespace($this->namespace . '\\Admin')
                        ->group($this->basePath . 'admin.php');

                    Route::prefix('media')
                        ->middleware('api')
                        ->namespace($this->namespace . '\\Media')
                        ->group($this->basePath . 'media.php');

                    Route::prefix('general')
                        ->middleware('api')
                        ->namespace($this->namespace . '\\General')
                        ->group($this->basePath . 'general.php');

                    Route::prefix('events')
                        ->middleware('api')
                        ->namespace($this->namespace . '\\Event')
                        ->group($this->basePath . 'events.php');

                    Route::prefix('shows')
                        ->middleware('api')
                        ->namespace($this->namespace . '\\Show')
                        ->group($this->basePath . 'shows.php');

                    Route::prefix('persons')
                        ->middleware('api')
                        ->namespace($this->namespace . '\\Person')
                        ->group($this->basePath . 'persons.php');

                    Route::prefix('collectives')
                        ->middleware('api')
                        ->namespace($this->namespace . '\\Collective')
                        ->group($this->basePath . 'collectives.php');

                    Route::prefix('venues')
                        ->middleware('api')
                        ->namespace($this->namespace . '\\Venue')
                        ->group($this->basePath . 'venues.php');
                });
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ? : $request->ip());
        });
    }
}
