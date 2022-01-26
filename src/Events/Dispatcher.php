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

namespace Platform\Events;

class Dispatcher extends \Illuminate\Events\Dispatcher
{
    /**
     * Simple method to return all listeners registered in the system.
     *
     * @return array
     */
    public function getAllListeners(): array
    {
        return $this->listeners;
    }

    /**
     * Same as dispatch. Kept for backwards compatibility.
     *
     * @param $event
     * @param array $payload
     * @param bool $halt
     * @return array|null
     */
    public function fire($event, $payload = [], $halt = false): ?array
    {
        return $this->dispatch($event, $payload, $halt);
    }
}
