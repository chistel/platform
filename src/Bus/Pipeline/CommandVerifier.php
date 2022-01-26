<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           CommandVerifier.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Bus\Pipeline;

use Illuminate\Contracts\Container\Container;

class CommandVerifier
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function handle($command, $next)
    {
        $commandName  = get_class($command);
        $verifierName = $commandName.'Verifier';

        if (class_exists($verifierName)) {
            $verifier = $this->app->make($verifierName);

            $verifier->verify($command);
        }

        return $next($command);
    }
}
