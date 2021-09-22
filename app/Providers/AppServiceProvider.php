<?php

namespace App\Providers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\ServiceProvider;
use URL;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.use_https')) {
            URL::forceScheme('https');
        }
    }
}
