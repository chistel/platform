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

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\AggregateServiceProvider;
use Illuminate\Support\Collection;
use Platform\Menu\Manager as MenuManager;
use Illuminate\Contracts\Container\BindingResolutionException;

class PlatformServiceProvider extends AggregateServiceProvider
{
    protected $providers = [
        BusServiceProvider::class,
        QueryServiceProvider::class,
        MixinServiceProvider::class,
        MediaLibraryServiceProvider::class,
        AfricasTalkingServiceProvider::class,
        MenuServiceProvider::class
    ];

    public function register()
    {
        parent::register();

        $this->registerMenu();

        $this->mergeConfigFrom(__DIR__ . '/../../config/platform.php', 'platform');

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'platform');
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot()
    {
       // $this->publishMigrations();

        $this->publishes([
            __DIR__ . '/../../config/platform.php' => $this->configPath('platform.php'),
        ], 'platform-config');

        $this->publishes([
            __DIR__ . '/../../resources/views' => base_path('resources/views/vendor/platform'),
        ], 'platform-view');
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

    /**
     * @throws BindingResolutionException
     */
  /*  private function publishMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../database/migrations/create_banks_table.php.stub' => $this->getMigrationFileName('create_banks_table.php'),
                __DIR__.'/../../database/migrations/create_cities_table.php.stub' => $this->getMigrationFileName('create_cities_table.php'),
                __DIR__.'/../../database/migrations/create_connected_accounts_table.php.stub' => $this->getMigrationFileName('create_connected_accounts_table.php'),
                __DIR__.'/../../database/migrations/create_currencies_table.php.stub' => $this->getMigrationFileName('create_currencies_table.php'),
                __DIR__.'/../../database/migrations/create_currency_exchange_rates_table.php.stub' => $this->getMigrationFileName('create_currency_exchange_rates_table.php'),
                __DIR__.'/../../database/migrations/create_levels_table.php.stub' => $this->getMigrationFileName('create_levels_table.php.stub'),
            ], 'platform-migration');
        }
    }*/

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param $migrationFileName
     * @return string
     * @throws BindingResolutionException
     */
    protected function getMigrationFileName($migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}