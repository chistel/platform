<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           PlatformServiceProvider.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 7:22 PM
 */

namespace Platform\Providers;

use Illuminate\Support\AggregateServiceProvider;
use Platform\Menu\Manager as MenuManager;
use ProtoneMedia\LaravelFFMpeg\Support\ServiceProvider;
use Illuminate\Contracts\Container\BindingResolutionException;

class PlatformServiceProvider extends AggregateServiceProvider
{
    protected $providers = [
        ServiceProvider::class,
        BusServiceProvider::class,
        MixinServiceProvider::class,
        MediaLibraryServiceProvider::class,
        //LaravelFFMpegServiceProvider::class,
        AfricasTalkingServiceProvider::class,
        MenuServiceProvider::class
    ];

    public function register()
    {
        parent::register();

        $this->registerMenu();

        $this->mergeConfigFrom(__DIR__ . '/../../config/platform.php', 'platform');
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $this->publishMigrations();

        $this->publishes([
            __DIR__ . '/../../config/platform.php' => $this->configPath('platform.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../../resources/views' => base_path('resources/views/vendor/platform'),
        ]);

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'platform');
    }

    private function registerMenu()
    {
        $this->app->singleton(MenuManager::class);
    }

    /**
     * Implementation of config path that doesn't require a helper.
     *
     * @param string $path
     * @return string
     * @throws BindingResolutionException
     */
    private function configPath(string $path): string
    {
        return $this->app->make('path.config') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    private function publishMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../database/migrations/' => database_path('migrations'),
            ], 'migrations');
        }
    }
}