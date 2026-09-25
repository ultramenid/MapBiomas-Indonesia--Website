<?php

namespace App\Providers;

use App\Models\Partner;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.footer', function ($view) {
            $view->with([
                'coCreators' => Partner::active()->where('category', Partner::CATEGORY_COCREATOR)->get(),
                'supporters' => Partner::active()->where('category', Partner::CATEGORY_SUPPORTED)->get(),
            ]);
        });
    }
}
