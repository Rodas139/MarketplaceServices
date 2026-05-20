<?php

namespace App\Providers;

use App\Services\CategoryService;
use App\Services\ComissionService;
use App\Services\CommentService;
use App\Services\CustomerService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\ServiceService;
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
        $this->app->singleton(CategoryService::class, function($app) {
            return new CategoryService();
        });

        $this->app->singleton(ComissionService::class, function($app) {
            return new ComissionService();
        });

        $this->app->singleton(CommentService::class, function($app) {
            return new CommentService();
        });

        $this->app->singleton(CustomerService::class, function($app) {
            return new CustomerService();
        });

        $this->app->singleton(OrderService::class, function($app) {
            return new OrderService();
        });

        $this->app->singleton(PaymentService::class, function($app) {
            return new PaymentService();
        });

        $this->app->singleton(ServiceService::class, function($app) {
            return new ServiceService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require resource_path() . '/helpers/macros.php';
        require resource_path() . '/helpers/functions.php';
    }
}

