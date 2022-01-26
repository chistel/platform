<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           MenuServiceProvider.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 7:22 PM
 */

namespace Platform\Providers;

use Illuminate\Support\ServiceProvider;
use Platform\Menu\Manager;
use Platform\Menu\Menufy;

/**
 * @codeCoverageIgnore
 */
class MenuServiceProvider extends ServiceProvider
{
    protected array $aliases = [
        'Menufy' => Menufy::class
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('menufy', Manager::class);
    }

    public function provides()
    {
        return ['menufy', 'Menufy'];
    }
}
