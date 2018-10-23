<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Auth;
use App\Modulo;

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


    function is_admin()
    {
        return Auth::check() & Auth::user()->role_id == 1;
    }

    function get_modules()
    {	
        $modules = Modulo::all();          

        
        return $modules;
    }
}
