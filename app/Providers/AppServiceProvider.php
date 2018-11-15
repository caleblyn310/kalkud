<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //view()->composer('kaskecil.form', function ($view) {
           // $view->with('kode_unit', Customuser::where('nama',auth()->user()->name)->value('kode_cabang')); });
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
