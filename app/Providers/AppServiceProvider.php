<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Facades\AliasMethod;
use App\Facades\GeoLookup;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('aliasmethod',function(){
            return new AliasMethod;
        });
        $this->app->singleton('geolookup',function(){
            return new GeoLookup;
        });
    }
}
