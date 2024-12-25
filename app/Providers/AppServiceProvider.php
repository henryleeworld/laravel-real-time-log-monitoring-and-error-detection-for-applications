<?php

namespace App\Providers;

use App\Contracts\ServerProvider;
use App\Services\LogHandlerService;
use Illuminate\Support\ServiceProvider;
use Saasscaleup\LogAlarm\LogHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        LogHandler::class => LogHandlerService::class,
    ];

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
        //
    }
}
