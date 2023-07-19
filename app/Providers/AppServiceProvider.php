<?php

namespace App\Providers;

use App\Models\Car;
use App\Models\Notification;
use App\Observers\CarObserver;
use App\Observers\NotificationObserver;
use Illuminate\Support\Facades\Schema;
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
        date_default_timezone_set('Europe/Bucharest');
        Schema::defaultStringLength(191);

        Car::observe(CarObserver::class);
        Notification::observe(NotificationObserver::class);
    }
}
