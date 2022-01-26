<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           AfricasTalkingServiceProvider.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/12/2021, 12:07 PM
 */

namespace Platform\Providers;

use Illuminate\Support\ServiceProvider;
use Platform\Exceptions\InvalidConfiguration;
use AfricasTalking\SDK\AfricasTalking as AfricasTalkingSDK;
use Platform\Services\Channels\AfricasTalking\AfricasTalkingChannel;

class AfricasTalkingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /**
         * Bootstrap the application services.
         */
        $this->app->when(AfricasTalkingChannel::class)
            ->needs(AfricasTalkingSDK::class)
            ->give(function () {
                $userName = config('platform.africastalking.username');
                $key = config('platform.africastalking.key');
                if (is_null($userName) || is_null($key)) {
                    throw InvalidConfiguration::configurationNotSet();
                }
                return new AfricasTalkingSDK(
                    $userName,
                    $key
                );
            });
    }
}
