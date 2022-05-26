<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           EventDispatcher.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 10:11 PM
 */

namespace Platform\Database\Traits;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;

trait EventDispatcher
{
    /**
     * Loop through all events and fire them.
     *
     * @param array|object $events
     * @throws BindingResolutionException
     */
    public function dispatch(array|object $events)
    {
        if (!is_array($events)) {
            $events = [$events];
        }

        foreach (array_filter($events) as $event) {
            $this->logEvent($event);
            $this->fireEvent($event);
        }
    }

    /**
     * Dispatch all events provided.
     *
     * @param array $events
     * @throws BindingResolutionException
     */
    public function dispatchAll(array $events)
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    /**
     * Logs the event provided.
     *
     * @param object $event
     */
    protected function logEvent($event)
    {
        $class = get_class($event);

        Log::info("Event [$class] fired", get_object_vars($event));
    }

    /**
     * Fires the event provided.
     *
     * @param object $event
     * @throws BindingResolutionException
     */
    protected function fireEvent($event)
    {
        Container::getInstance()->make('events')->dispatch($event);
    }
}
