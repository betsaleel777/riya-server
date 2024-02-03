<?php

namespace App\Providers;

use App\Interfaces\AchatRepositoryInterface;
use App\Interfaces\BienRepositoryInterface;
use App\Interfaces\ContratRepositoryInterface;
use App\Interfaces\DetteRepositoryInterface;
use App\Interfaces\LoyerRepositoryInterface;
use App\Interfaces\PaiementRepositoryInterface;
use App\Interfaces\VisiteRepositoryInterface;
use App\Repositories\AchatRepository;
use App\Repositories\BienRepository;
use App\Repositories\ContratRepository;
use App\Repositories\DetteRepository;
use App\Repositories\LoyerRepository;
use App\Repositories\PaiementRepository;
use App\Repositories\VisiteRepository;
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
        $this->app->bind(PaiementRepositoryInterface::class, PaiementRepository::class);
        $this->app->bind(DetteRepositoryInterface::class, DetteRepository::class);
        $this->app->bind(VisiteRepositoryInterface::class, VisiteRepository::class);
        $this->app->bind(LoyerRepositoryInterface::class, LoyerRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
