<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Providers.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     19/10/2021, 12:24 AM
 */

namespace Platform\Services\Connected;

class Providers
{
    /**
     * Determine if the given provider is enabled.
     *
     * @param  string  $provider
     * @return bool
     */
    public static function enabled(string $provider): bool
    {
        return in_array($provider, config('platform.connected_providers', []));
    }

    /**
     * Determine if the application has support for the Facebook provider.
     *
     * @return bool
     */
    public static function hasFacebookSupport(): bool
    {
        return static::enabled(static::facebook());
    }

    /**
     * Determine if the application has support for the Google provider.
     *
     * @return bool
     */
    public static function hasGoogleSupport(): bool
    {
        return static::enabled(static::google());
    }

    /**
     * Determine if the application has support for the LinkedIn provider.
     *
     * @return bool
     */
    public static function hasLinkedInSupport()
    {
        return static::enabled(static::linkedin());
    }

    /**
     * Determine if the application has support for the LinkedIn provider.
     *
     * @return bool
     */
    public static function hasTwitterSupport(): bool
    {
        return static::enabled(static::twitter());
    }

    /**
     * Enable the Facebook provider.
     *
     * @return string
     */
    public static function facebook()
    {
        return 'facebook';
    }

    /**
     * Enable the google provider.
     *
     * @return string
     */
    public static function google()
    {
        return 'google';
    }

    /**
     * Enable the linkedin provider.
     *
     * @return string
     */
    public static function linkedin()
    {
        return 'linkedin';
    }

    /**
     * Enable the twitter provider.
     *
     * @return string
     */
    public static function twitter()
    {
        return 'twitter';
    }
}
