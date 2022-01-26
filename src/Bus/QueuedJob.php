<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           QueuedJob.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Bus;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Platform\Bus\Pipeline\Applicationable;

abstract class QueuedJob implements ShouldQueue, Applicationable
{
    use Queueable, SerializesModels, SerialisesDeletedModels {
        SerialisesDeletedModels::getRestoredPropertyValue insteadof SerializesModels;
    }

    /**
     * @var mixed
     */
    public $dispatchedBy;

    /**
     * @param Queue $queue
     * @return mixed
     */
    public function queue(Queue $queue)
    {
        if ($this->synchronous()) {
            return $this->pushToQueue($queue);
        }

        foreach (get_class_methods($this) as $method) {
            if ($this->startsWithQueue($method)) {
                $this->$method();
            }
        }

        return $this->pushToQueue($queue);
    }

    /**
     * Whenever a queued job is used, the job should specify who it was dispatched by.
     *
     * @return mixed
     */
    abstract protected function queueDispatchedBy();

    /**
     * @param Queue $queue
     * @return mixed
     */
    private function pushToQueue(Queue $queue)
    {
        if (isset($this->delay)) {
            return $queue->later($this->delay, $this, '', $this->queue ?? null);
        }

        return $queue->push($this, '', $this->queue ?? null);
    }

    /**
     * Checks if the current queue processing environment is synchronous.
     *
     * @return bool
     */
    private function synchronous(): bool
    {
        return env('QUEUE_DRIVER') == 'sync';
    }

    /**
     * Checks if the method name starts with `queue` but isn't only `queue`
     *
     * @param string $method
     * @return bool
     */
    private function startsWithQueue(string $method): bool
    {
        return $method != 'queue' && Str::startsWith($method, 'queue');
    }
}
