<?php

namespace App\Providers;

use App\Models\AppointmentRequest;
use App\Models\Car;
use App\Models\Alert;
use App\Models\UserDocument;
use App\Models\UserImage;
use App\Observers\AppointmentRequestObserver;
use App\Observers\CarObserver;
use App\Observers\AlertObserver;
use App\Observers\UserDocumentObserver;
use App\Observers\UserImageObserver;
use App\Services\AlertService;
use App\Services\ImageService;
use App\Services\FirebaseService;
use App\Services\SmsService;
use App\Services\UserService;
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

        $this->app->bind(AlertService::class, function ($app) {
            return new AlertService();
        });

        $this->app->bind(FirebaseService::class, function ($app) {
            return new FirebaseService();
        });

        $this->app->bind(UserService::class, function ($app) {
            return new UserService();
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

        UserImage::observe(UserImageObserver::class);
        UserDocument::observe(UserDocumentObserver::class);
    }
}
