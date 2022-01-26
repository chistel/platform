<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           MediaOpenerFactory.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Services\LaravelFFMpeg;

use Illuminate\Support\Traits\ForwardsCalls;
use ProtoneMedia\LaravelFFMpeg\Drivers\PHPFFMpeg;
use ProtoneMedia\LaravelFFMpeg\Http\DynamicHLSPlaylist;

class MediaOpenerFactory
{
    use ForwardsCalls;

	/**
	 * @var string
	 */
	private string $defaultDisk;
	/**
	 * @var PHPFFMpeg
	 */
	private PHPFFMpeg $driver;

	/**
	 * MediaOpenerFactory constructor.
	 * @param string $defaultDisk
	 * @param PHPFFMpeg $driver
	 */
    public function __construct(string $defaultDisk, PHPFFMpeg $driver)
    {
        $this->defaultDisk = $defaultDisk;
        $this->driver      = $driver;
    }

	/**
	 * @return MediaOpener
	 */
    public function new(): MediaOpener
    {
        return new MediaOpener($this->defaultDisk, $this->driver);
    }

	/**
	 * @return DynamicHLSPlaylist
	 */
    public function dynamicHLSPlaylist(): DynamicHLSPlaylist
    {
        return new DynamicHLSPlaylist($this->defaultDisk);
    }

    /**
    * Handle dynamic method calls into the MediaOpener.
    *
    * @param  string  $method
    * @param  array  $parameters
    * @return mixed
    */
    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->new(), $method, $parameters);
    }
}
