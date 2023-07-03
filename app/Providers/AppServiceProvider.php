<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Setting;
use Facade\FlareClient\View;
use Illuminate\Pagination\Paginator;
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
        $setting = Setting::first();
        $categories = Category::where('parent_id', 0)->where('is_active',1)->orderby('priority','asc')->get();
        \view()->share(compact('setting','categories'));
        Paginator::useBootstrap();
    }
}
