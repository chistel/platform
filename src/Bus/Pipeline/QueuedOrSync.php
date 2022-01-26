<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           QueuedOrSync.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Bus\Pipeline;

use Illuminate\Contracts\Queue\ShouldQueue;

trait QueuedOrSync
{
    /**
     * Returns true if the command ShouldQueue and the queue driver isn't sync.
     *
     * @param mixed $command
     * @return bool
     */
    protected function isQueued($command): bool
    {
        return config('queue.default') != 'sync' && $command instanceof ShouldQueue;
    }

    /**
     * Returns true if the command is running synchronously, either due to lack of ShouldQueue or the sync driver.
     *
     * @param mixed $command
     * @return bool
     */
    protected function isSync($command): bool
    {
        return ! $this->isQueued($command);
    }
}
