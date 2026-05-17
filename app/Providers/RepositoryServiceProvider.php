<?php

namespace App\Providers;

use App\Repositories\InterestCatalogRepositoryInterface;
use App\Repositories\LanguageRepositoryInterface;
use App\Repositories\PersonRepositoryInterface;
use App\Repositories\Eloquent\InterestCatalogRepository;
use App\Repositories\Eloquent\LanguageRepository;
use App\Repositories\Eloquent\PersonRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PersonRepositoryInterface::class, PersonRepository::class);
        $this->app->bind(LanguageRepositoryInterface::class, LanguageRepository::class);
        $this->app->bind(InterestCatalogRepositoryInterface::class, InterestCatalogRepository::class);
    }
}
