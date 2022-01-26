<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           QueuedCommandHasFinished.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Events;

use Platform\Bus\QueuedJob;
use Platform\Bus\Status\Messenger;
use Platform\Bus\Pipeline\Userable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class QueuedCommandHasFinished implements ShouldBroadcastNow
{
    use UserBroadcaster;

    /**
     * @var mixed
     */
    public $resource;

    /**
     * @var Userable
     */
    public $command;

    /**
     * @var Authenticatable
     */
    public $user;

    /**
     * @param QueuedJob $command
     * @param $resource
     * @param Authenticatable $user
     */
    public function __construct(QueuedJob $command, $resource, Authenticatable $user)
    {
        $this->resource = $resource;
        $this->command = $command;
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message' => $this->message(),
            'type' => 'info',
            'refreshIfExists' => $this->resource->getBroadcastRefreshToken(),
        ];
    }

    /**
     * Returns the consumer to be used for the broadcast.
     *
     * @return Authenticatable
     */
    protected function user()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    private function message(): string
    {
        if ($this->resource instanceof Messenger && $message = $this->resource->finishMessage()) {
            return $message;
        }
        $resourceKey = $this->resource->getBroadcastKey();
        $name = method_exists($this->resource, 'getBroadcastName') ? $this->resource->getBroadcastName() : '';

        return trans('broadcast.message.finished.'.$resourceKey, ['name' => $name]);
    }
}
