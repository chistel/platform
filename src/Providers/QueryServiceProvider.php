<?php
namespace Platform\Providers;

use Illuminate\Support\ServiceProvider;
use Platform\Database\QueryLoggerService;

class QueryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('querylogger', QueryLoggerService::class);
    }
}
