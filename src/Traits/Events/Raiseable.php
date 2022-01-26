<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Raiseable.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 8:36 AM
 */

namespace Platform\Traits\Events;

/**
 * Trait EventGenerator
 *
 * Implemented in classes that need to raise events.
 */
trait Raiseable
{
    /** @var array  */
    protected array $pendingEvents = [];

    /**
     * Raise a new event, appending the event to the events array on the class.
     *
     * @param $event
     */
    public function raise($event)
    {
        $this->pendingEvents[] = $event;
    }

    /**
     * Raise a new event, discarding other events from the same class that were previously raised.
     *
     * @param $event
     */
    public function raiseUnique($event)
    {
        $this->discardExisting(get_class($event));

        $this->pendingEvents[] = $event;
    }

    /**
     * Discard all raised events of the given class
     *
     * @param string $class
     */
    private function discardExisting(string $class)
    {
        $this->pendingEvents = collect($this->pendingEvents)->reject(function ($existing) use ($class) {
            return $existing instanceof $class;
        })->all();
    }

    /**
     * Raise all the events again on this particular class.
     *
     * @param array $events
     */
    public function raiseAll(array $events)
    {
        $this->pendingEvents = array_merge($this->pendingEvents, $events);
    }

    /**
     * Returns and clears the pending events array.
     *
     * @return array
     */
    public function releaseEvents(): array
    {
        $events = $this->pendingEvents;

        $this->pendingEvents = [];

        return $events;
    }

    /**
     * Returns all pending events, but leaves state as-is.
     *
     * @return array
     */
    public function pendingEvents(): array
    {
        return $this->pendingEvents;
    }
}