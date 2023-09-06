<?php

namespace App\Providers;

use App\Models\AppointmentRequest;
use App\Models\Car;
use App\Models\Alert;
use App\Observers\AppointmentRequestObserver;
use App\Observers\CarObserver;
use App\Observers\AlertObserver;
use App\Services\ImageService;
use App\Services\SmsService;
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
        $this->app->bind(ImageService::class, function ($app) {
            return new ImageService();
        });
        $this->app->bind(SmsService::class, function ($app) {
            return new SmsService();
        });
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
        Alert::observe(AlertObserver::class);
        AppointmentRequest::observe(AppointmentRequestObserver::class);
    }
}
