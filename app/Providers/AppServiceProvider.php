<?php

namespace App\Providers;

use App\Services\FileManager\DirectoryManagerService;
use App\Services\FileManager\Interfaces\DirectoryManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DirectoryManager::class, DirectoryManagerService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
