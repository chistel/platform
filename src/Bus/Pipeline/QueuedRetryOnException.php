<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           QueuedRetryOnException.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Bus\Pipeline;

use Closure;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Log\LoggerInterface as Log;

class QueuedRetryOnException
{
    use QueuedOrSync;

    /**
     * @var Log
     */
    private $log;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @param Log $log
     * @param Dispatcher $dispatcher
     */
    public function __construct(\Psr\Log\LoggerInterface $log, Dispatcher $dispatcher)
    {
        $this->log = $log;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param mixed $command
     * @param Closure $next
     * @return mixed
     */
    public function handle($command, Closure $next)
    {
        if ($this->shouldRetry($command)) {
            return $this->attemptCommand($command, $next);
        }

        return $next($command);
    }

    /**
     * @param mixed $command
     * @param Closure $next
     * @throws \Throwable
     * @return mixed
     */
    private function attemptCommand($command, Closure $next)
    {
        try {
            return $next($command);
        } catch (\Throwable $throwable) {
            if (!$command->remainingAttempts()) {
                throw $throwable;
            }

            $this->log->notice('Command '.class_basename($command).' failed, retrying...', [
                'command' => get_class($command),
                'remainingAttempts' => $command->remainingAttempts(),
                'throwable' => $throwable->getMessage(),
            ]);

            // Retry command, delayed for 5 minutes
            $this->dispatcher->dispatch($command->retry());
        }
    }

    private function shouldRetry($command): bool
    {
        return $this->isQueued($command)
            && in_array(RetryOnException::class, class_uses($command));
    }
}
