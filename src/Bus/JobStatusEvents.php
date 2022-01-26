<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           JobStatusEvents.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Bus;

interface JobStatusEvents
{
    /**
     * Return events raised by the resource.
     * use \Platform\Events\Raiseable ;-)
     *
     * @return array
     */
    public function releaseEvents();
}