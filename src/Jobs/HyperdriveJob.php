<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           HyperdriveJob.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 8:36 AM
 */

namespace Platform\Jobs;

use Platform\Bus\RetriesJob;
use Platform\Bus\ShouldRetry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Platform\Kessel\Hyperdrive;

class HyperdriveJob implements ShouldQueue, ShouldRetry
{
    use RetriesJob;

    public $method;
    public $endpoint;
    public $payload;
    public $maxTries;

    public function __construct($method, $endpoint, $payload = [], $maxTries = 0, $queue = 'high')
    {
        $this->method = $method;
        $this->endpoint = $endpoint;
        $this->payload = $payload;
        $this->maxTries = $maxTries;
        $this->queue = $queue;
    }

    public function run()
    {
        app(Hyperdrive::class)->{$this->method}(
            $this->endpoint,
            $this->payload
        );
    }

    public function maxTries(): int
    {
        return $this->maxTries;
    }
}