<?php

namespace App\Providers;

use App\Models\Interest;
use App\Models\Language;
use App\Models\Person;
use App\Repositories\Contracts\InterestCatalogRepositoryInterface;
use App\Repositories\Contracts\LanguageRepositoryInterface;
use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Repositories\Eloquent\InterestCatalogRepository;
use App\Repositories\Eloquent\LanguageRepository;
use App\Repositories\Eloquent\PersonRepository;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Sanctum::ignoreMigrations();

        $this->app->bind(PersonRepositoryInterface::class, function () {
            return new PersonRepository(new Person());
        });

        $this->app->bind(LanguageRepositoryInterface::class, function () {
            return new LanguageRepository(new Language());
        });

        $this->app->bind(InterestCatalogRepositoryInterface::class, function () {
            return new InterestCatalogRepository(new Interest());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
