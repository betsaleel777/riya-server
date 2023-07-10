<?php

namespace App\Providers;

use App\Interfaces\AchatRepositoryInterface;
use App\Interfaces\BienRepositoryInterface;
use App\Interfaces\ContratRepositoryInterface;
use App\Repositories\AchatRepository;
use App\Repositories\BienRepository;
use App\Repositories\ContratRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ContratRepositoryInterface::class, ContratRepository::class);
        $this->app->bind(AchatRepositoryInterface::class, AchatRepository::class);
        $this->app->bind(BienRepositoryInterface::class, BienRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
