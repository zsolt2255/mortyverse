<?php

namespace App\Providers;

use App\Repositories\Eloquent\CharacterRepository;
use App\Repositories\Eloquent\Contracts\CharacterRepositoryInterface;
use App\Repositories\Eloquent\Contracts\EloquentRepositoryInterface;
use App\Repositories\Eloquent\Contracts\EpisodeRepositoryInterface;
use App\Repositories\Eloquent\EloquentRepository;
use App\Repositories\Eloquent\EpisodeRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(EloquentRepositoryInterface::class, EloquentRepository::class);
        $this->app->bind(EpisodeRepositoryInterface::class, EpisodeRepository::class);
        $this->app->bind(CharacterRepositoryInterface::class, CharacterRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
