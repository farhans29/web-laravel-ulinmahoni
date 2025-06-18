<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class ContentServiceProvider extends ServiceProvider
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
    public function boot()
    {
        $this->CRM_ISS = DB::table('global_title')->select('key')->where('key', 'OS Name')->first();
        
        view()->composer('*', function ($view) {
            $view->with([
                'CRM_ISS' => $this->CRM_ISS,
            ]);
        });
    }
}
