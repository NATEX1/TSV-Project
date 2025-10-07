<?php

namespace App\Providers;

use App\Services\MongoService;
use Illuminate\Support\Facades\URL;
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
       /*  $mongo = new MongoService();

        View::composer('*', function ($view) use ($mongo) {
            $contact = $mongo->findOne('contact');
            $view->with('contact', $contact);
        }); */

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
