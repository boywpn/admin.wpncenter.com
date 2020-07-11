<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Modules\Platform\Core\ViewComposers\MainMenuComposer;
use Modules\Platform\Settings\ViewComposers\SettingsMenuComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        \View::composer(
            'partial.left-sidebar',
            MainMenuComposer::class
        );

        \View::composer(
            'settings::partial.menu',
            SettingsMenuComposer::class
        );

        \Blade::if('issetbap', function ($array, $key) {
            if (array_key_exists($key, $array)) {
                return isset($array[$key]) && $array[$key];
            }
        });


        \Blade::if('fieldPermission', function ($options) {

            if(isset($options['permission']) && \Auth::user()->hasPermissionTo($options['permission'])){
                return true;
            }
            if(!isset($options['permission'])){
                return true;
            }
            return false;
        });



    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
