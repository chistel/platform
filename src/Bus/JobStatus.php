<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           JobStatus.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Bus;

use Illuminate\Support\Collection;
use Platform\Events\EventDispatcher;
use Platform\Events\QueuedCommandHasFinished;
use Illuminate\Contracts\Auth\Authenticatable;

trait JobStatus
{
    use Debounce, EventDispatcher;

    public function queueJobStatus()
    {
        $this->setQueued();
    }

    public function setQueued()
    {
        $this->updateJobStatusResource(function ($resource) {
            $resource->setJobStatus('queued');
        });
    }

    public function setProcessing()
    {
        $this->updateJobStatusResource(function ($resource) {
            $resource->setJobStatus('processing');
        });
    }

    public function setFinished()
    {
        $this->updateJobStatusResource(function ($resource) {
            if (method_exists($resource, 'fresh')) {
                $resource = $resource->fresh();
            }

            if (!$resource || $resource->getJobStatus() == 'queued') {
                return;
            }

            $resource->setJobStatus(null);

            if (! $resource instanceof JobStatusEvents) {
                $this->dispatch(new QueuedCommandHasFinished($this, $resource, $this->user()));
            }
        });
    }

    private function updateJobStatusResource(callable $callback)
    {
        $resource = $this->jobStatusResource();

        if (! $resource instanceof Collection) {
            $resource = new Collection(is_array($resource) ? $resource : [$resource]);
        }

        $resource->each($callback);
        $this->dispatch($resource->releaseEvents());
    }

    /**
     * Return the user that the command is for.
     *
     * @return Authenticatable
     */
    abstract public function user();

    /**
     * Return the JobStatus resource class, either a Model using JobStatusModel
     * or the CachedJobStatus class.
     *
     * @return mixed
     */
    abstract protected function jobStatusResource();
}
