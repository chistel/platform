<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           UserBroadcaster.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Events;

use AwardForce\Library\Facades\Broadcast as BroadcastFacade;
use Illuminate\Contracts\Auth\Authenticatable;

trait UserBroadcaster
{
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [
            BroadcastFacade::consumerChannel($this->consumer()),
        ];
    }

    /**
     * @return string
     */
    public function broadcastAs()
    {
        return BroadcastFacade::simpleMessage();
    }

    /**
     * Returns the consumer to be used for the broadcast.
     *
     * @return Authenticatable
     */
    abstract protected function user();
}
