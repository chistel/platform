<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Dispatcher.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Bus;

use Illuminate\Contracts\Container\BindingResolutionException;

class Dispatcher extends \Illuminate\Bus\Dispatcher
{
    /**
     * @param mixed $command
     * @return bool|mixed
     * @throws BindingResolutionException
     */
    public function getCommandHandler($command)
    {
        $class = get_class($command).'Handler';

        return class_exists($class) ? $this->container->make($class) : false;
    }
}
