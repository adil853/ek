<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\TimeRepositoryInterface;
use App\Infrastructure\Persistence\TimeRepository;

class TimeRepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(TimeRepositoryInterface::class, TimeRepository::class);
    }
}
