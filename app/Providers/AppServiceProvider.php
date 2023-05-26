<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

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
        // Mengatasi jika https tidak menjalankan css dan JS
        // if(env('APP_ENV') !== 'local'){
        //     URL::forceScheme('https');
        // }
        // --------------------------------------------------
        
        Blade::directive('currencyEncode', function ($number) {
            return "<?= 'Rp '.strrev(implode('.',str_split(strrev(strval($number)),3))) ?>";
        });
        Blade::directive('currencyDecode', function ($number) {
            $data = intval(preg_replace('/,.*|[^0-9]/', '', $number));
            return "<?= $data ?>";
        });

        // \URL::forceScheme('https');

        Paginator::useBootstrap();
        Schema::defaultStringLength(191);
    }
}
