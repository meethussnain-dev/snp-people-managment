<?php

namespace App\Providers;

use App\Repositories\Interest\InterestRepository;
use App\Repositories\Interest\InterestRepositoryInterface;
use App\Repositories\Language\LanguageRepository;
use App\Repositories\Language\LanguageRepositoryInterface;
use App\Repositories\Person\PersonRepository;
use App\Repositories\Person\PersonRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PersonRepositoryInterface::class, PersonRepository::class);
        $this->app->bind(LanguageRepositoryInterface::class, LanguageRepository::class);
        $this->app->bind(InterestRepositoryInterface::class, InterestRepository::class);
    }
}
