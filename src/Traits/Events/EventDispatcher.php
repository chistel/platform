<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           EventDispatcher.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 8:36 AM
 */

namespace Platform\Traits\Events;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;

trait EventDispatcher
{
    /**
     * Loop through all events and fire them.
     *
     * @param object|array $events
     * @throws BindingResolutionException
     */
    public function dispatch(object|array $events)
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
    protected function logEvent(object $event)
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
    protected function fireEvent(object $event)
    {
        Container::getInstance()->make('events')->dispatch($event);
    }
}