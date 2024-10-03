<?php

namespace App\Providers;

use App\Interfaces\FileRepositoryInterface;
use App\Repositories\FileRepository;
use App\Services\FileManager\DirectoryManagerService;
use App\Services\FileManager\FileManagerService;
use App\Services\FileManager\Interfaces\DirectoryManager;
use App\Services\FileManager\Interfaces\FileManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DirectoryManager::class, DirectoryManagerService::class);
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
        $this->app->bind(FileManager::class, FileManagerService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
