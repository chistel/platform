<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           CommandTransaction.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Bus\Pipeline;

use Closure;
use Exception;
use Illuminate\Support\Facades\DB;
use Platform\Bus\Contracts\DatabaseTransactions;

class CommandTransaction
{
    /**
     * @param mixed   $command
     * @param Closure $next
     * @throws Exception
     * @return mixed
     */
    public function handle($command, Closure $next)
    {
        if ($command instanceof DatabaseTransactions) {
            return DB::transaction(function () use ($command, $next) {
                return $next($command);
            });
        }

        return $next($command);
    }
}
