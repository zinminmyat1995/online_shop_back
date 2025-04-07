<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Classes\Repositories\UserRegistrationRepository;
use App\Classes\Repositories\UserListRepository;
use App\Classes\Repositories\ProductRegistrationRepository;
use App\Classes\Repositories\ProductListRepository;
use App\Classes\Repositories\ImportRegistrationRepository;
use App\Classes\Repositories\NGRegistrationRepository;
use App\Classes\Repositories\NGListRepository;
use App\Classes\Repositories\NGReturnRegistrationRepository;
use App\Classes\Repositories\NGReturnListRepository;
use App\Classes\Repositories\NGArriveRegistrationRepository;
use App\Classes\Repositories\NGArriveListRepository;
use App\Classes\Repositories\SettingRespository;
use App\Classes\Repositories\SaleRegistrationRepository;
use App\Classes\Repositories\InstockRepository;
use App\Classes\Repositories\SaleListRepository;
use App\Classes\Repositories\HistoryRepository;
use App\Classes\Repositories\DashboardRepository;

use App\Interfaces\{UserRegistrationInterface};
use App\Interfaces\{UserListInterface};
use App\Interfaces\{ProductRegistrationInterface};
use App\Interfaces\{ProductListInterface};
use App\Interfaces\{ImportRegistrationInterface};
use App\Interfaces\{NGRegistrationInterface};
use App\Interfaces\{NGListInterface};
use App\Interfaces\{NGReturnRegistrationInterface};
use App\Interfaces\{NGReturnListInterface};
use App\Interfaces\{NGArriveRegistrationInterface};
use App\Interfaces\{NGArriveListInterface};
use App\Interfaces\{SettingInterface};
use App\Interfaces\{SaleRegistrationInterface};
use App\Interfaces\{InstockInterface};
use App\Interfaces\{SaleListInterface};
use App\Interfaces\{HistoryInterface};
use App\Interfaces\{DashboardInterface};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRegistrationInterface::class,UserRegistrationRepository::class); 
        $this->app->bind(UserListInterface::class,UserListRepository::class); 
        $this->app->bind(ProductRegistrationInterface::class,ProductRegistrationRepository::class); 
        $this->app->bind(ProductListInterface::class,ProductListRepository::class); 
        $this->app->bind(ImportRegistrationInterface::class,ImportRegistrationRepository::class); 
        $this->app->bind(NGRegistrationInterface::class,NGRegistrationRepository::class); 
        $this->app->bind(NGListInterface::class,NGListRepository::class); 
        $this->app->bind(NGReturnRegistrationInterface::class,NGReturnRegistrationRepository::class); 
        $this->app->bind(NGReturnListInterface::class,NGReturnListRepository::class); 
        $this->app->bind(NGArriveRegistrationInterface::class,NGArriveRegistrationRepository::class); 
        $this->app->bind(NGArriveListInterface::class,NGArriveListRepository::class); 
        $this->app->bind(SettingInterface::class,SettingRespository::class); 
        $this->app->bind(SaleRegistrationInterface::class,SaleRegistrationRepository::class); 
        $this->app->bind(InstockInterface::class,InstockRepository::class); 
        $this->app->bind(SaleListInterface::class,SaleListRepository::class); 
        $this->app->bind(HistoryInterface::class,HistoryRepository::class); 
        $this->app->bind(DashboardInterface::class,DashboardRepository::class); 
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
