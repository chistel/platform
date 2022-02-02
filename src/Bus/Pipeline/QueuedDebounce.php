<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           QueuedDebounce.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Bus\Pipeline;

use Closure;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use Platform\Bus\Traits\Debounce;
use Platform\Bus\Traits\QueuedOrSync;

class QueuedDebounce
{
    use DispatchesJobs;
    use QueuedOrSync;

    public function handle($command, Closure $next)
    {
        if ($this->isSync($command) || !uses_trait($command, Debounce::class)) {
            return $next($command);
        }

        $outdated = $command->debounceOutdated();
        $overdue = $command->debounceOverdue();

        if ($outdated && ! $overdue) {
            Log::info('Debounce skipping outdated: '.get_class($command));
            return;
        }

        if ($command->debounceLocked()) {
            Log::info('Debounce delaying locked: '.get_class($command));
            return $this->dispatch($command->retryDebounce());
        }

        $command->lockDebounce();

        if (! $outdated) {
            $command->forgetDebouncePayload();
        }

        try {
            return $next($command);
        } catch (\Throwable $exception) {
            throw $exception;
        } finally {
            $command->unlockDebounce();
        }
    }
}
