<?php
namespace Tests;

use Closure;
use Mockery as m;
use Platform\Providers\PlatformServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->init();
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

    public function init()
    {
        // implement in children, if required
    }

    protected function getPackageProviders($app)
    {
        return [PlatformServiceProvider::class];
    }

    protected function getApplicationAliases($app)
    {
        return [

        ];
    }

    protected function mock($param, Closure $mock = null)
    {
        return m::mock($param);
    }

    protected function spy($param, Closure $mock = null)
    {
        return m::spy($param);
    }

    private function setupConfiguration($app, ...$configFiles)
    {
        foreach ($configFiles as $file) {
            $app['config']->set($file, require(__DIR__ . '/../config/' . $file . '.php'));
        }
    }
}
