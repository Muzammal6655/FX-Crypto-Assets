<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(255);

        if (App::environment() === 'production') {
            shell_exec('sudo chown -R www-data:www-data /var/www/html/storage');
            shell_exec('sudo chmod -R 777 /var/www/html/storage');
        }
    }
}