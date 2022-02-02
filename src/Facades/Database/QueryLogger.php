<?php
namespace Platform\Facades\Database;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method void enable
 * @method void disable
 * @method Collection getAllQueries
 * @method Collection getMostOftenExecuted
 * @method Collection getMostTimeConsuming
 */
class QueryLogger extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'querylogger';
    }
}
