<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Foundation\Vite;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

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
        Filament::registerScripts([
            app(Vite::class)('resources/js/heatmap.js'),
        ]);

        Filament::serving(function () {
            Filament::registerTheme(
                app(Vite::class)('resources/css/filament.css'),
            );
        });

        Request::macro('anonymizedIdentifier', function () {
            return hash('sha512', base64_encode($this->ip().'.'.$this->userAgent()));
        });
    }
}
