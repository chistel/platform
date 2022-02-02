<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           RetryOnException.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Bus\Traits;

trait RetryOnException
{
    /**
     * @var int
     */
    private $retryAttempts = 0;

    /**
     * Returns the number of retries the command is allowed to attempt
     *
     * @return int
     */
    abstract protected function allowedRetries(): int;

    /**
     * Delay in seconds for the retry attempt, defaulting to between 1 - 5 minutes.
     * Can be overridden by a command that requires a different delay.
     *
     * @return int
     */
    protected function retryDelay(): int
    {
        return random_int(60, 300);
    }

    /**
     * @return int
     */
    public function remainingAttempts(): int
    {
        $remaining = $this->allowedRetries() - $this->retryAttempts;

        return $remaining > 0 ? $remaining : 0;
    }

    /**
     * @return RetryOnException
     */
    public function retry(): self
    {
        $this->retryAttempts++;
        $this->delay = $this->retryDelay();

        return $this;
    }
}
