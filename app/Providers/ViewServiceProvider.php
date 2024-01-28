<?php

namespace App\Providers;

//use Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot():void
    {

//        $permissions = $p->getAllPermissions();
//
//        foreach ($permissions as $p){
//            $permissionsNames[] = $p->name;
//        }
//        View::share('permissionsNames', $permissionsNames);
    }
}
