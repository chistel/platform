<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           QueuedJobStatus.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Bus\Pipeline;

use Closure;
use Platform\Bus\Traits\QueuedOrSync;
use Platform\Database\Traits\EventDispatcher;

class QueuedJobStatus
{
    use EventDispatcher;
    use QueuedOrSync;

    /**
     * @param mixed $command
     * @param Closure $next
     * @return mixed
     */
    public function handle($command, Closure $next)
    {
        if ($this->isSync($command)) {
            return $next($command);
        }

        if (method_exists($command, 'setProcessing')) {
            $command->setProcessing();
        }

        $response = $next($command);

        if (method_exists($command, 'setFinished')) {
            $command->setFinished();
        }

        return $response;
    }
}
