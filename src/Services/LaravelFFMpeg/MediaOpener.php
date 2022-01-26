<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           MediaOpener.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Services\LaravelFFMpeg;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Media\AbstractMediaType;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\ForwardsCalls;
use ProtoneMedia\LaravelFFMpeg\Drivers\PHPFFMpeg;
use Platform\Services\LaravelFFMpeg\Exporters\HLSExporter;
use ProtoneMedia\LaravelFFMpeg\Exporters\MediaExporter;
use ProtoneMedia\LaravelFFMpeg\Filesystem\Disk;
use ProtoneMedia\LaravelFFMpeg\Filesystem\Media;
use ProtoneMedia\LaravelFFMpeg\Filesystem\MediaCollection;
use ProtoneMedia\LaravelFFMpeg\Filesystem\MediaOnNetwork;
use ProtoneMedia\LaravelFFMpeg\Filesystem\TemporaryDirectories;

/**
 * @mixin PHPFFMpeg
 */
class MediaOpener
{
    use ForwardsCalls;

    /**
     * @var Disk
     */
	private Disk $disk;

    /**
     * @var PHPFFMpeg
     */
    private $driver;

    /**
     * @var MediaCollection
     */
    private $collection;

    /**
     * @var TimeCode
     */
    private $timecode;

	/**
	 * Uses the 'filesystems.default' disk from the config if none is given.
	 * Gets the underlying PHPFFMpeg instance from the container if none is given.
	 * Instantiates a fresh MediaCollection if none is given.
	 * @param null $disk
	 * @param PHPFFMpeg|null $driver
	 * @param MediaCollection|null $mediaCollection
	 */
    public function __construct($disk = null, PHPFFMpeg $driver = null, MediaCollection $mediaCollection = null)
    {
        $this->fromDisk($disk ?: config('filesystems.default'));

        $this->driver = ($driver ?: app(PHPFFMpeg::class))->fresh();

        $this->collection = $mediaCollection ?: new MediaCollection;
    }

    public function clone(): self
    {
        return new MediaOpener(
            $this->disk,
            $this->driver,
            $this->collection
        );
    }

    /**
     * Set the disk to open files from.
     */
    public function fromDisk($disk): self
    {
        $this->disk = Disk::make($disk);

        return $this;
    }

    /**
     * Alias for 'fromDisk', mostly for backwards compatibility.
     */
    public function fromFilesystem(Filesystem $filesystem): self
    {
        return $this->fromDisk($filesystem);
    }

    /**
     * Instantiates a Media object for each given path.
     */
    public function open($paths): self
    {
        foreach (Arr::wrap($paths) as $path) {
            $this->collection->push(Media::make($this->disk, $path));
        }

        return $this;
    }

    /**
     * Instantiates a single Media object and sets the given options on the object.
     *
     * @param string $path
     * @param array $options
     * @return self
     */
    public function openWithInputOptions(string $path, array $options = []): self
    {
        $this->collection->push(
            Media::make($this->disk, $path)->setInputOptions($options)
        );

        return $this;
    }

	/**
	 * Instantiates a MediaOnNetwork object for each given url.
	 * @param $paths
	 * @param array $headers
	 * @return MediaOpener
	 */
    public function openUrl($paths, array $headers = []): self
    {
        foreach (Arr::wrap($paths) as $path) {
            $this->collection->push(MediaOnNetwork::make($path, $headers));
        }

        return $this;
    }

	/**
	 * @return MediaCollection
	 */
    public function get(): MediaCollection
    {
        return $this->collection;
    }

	/**
	 * @return PHPFFMpeg
	 */
    public function getDriver(): PHPFFMpeg
    {
        return $this->driver->open($this->collection);
    }

    /**
     * Forces the driver to open the collection with the `openAdvanced` method.
     */
    public function getAdvancedDriver(): PHPFFMpeg
    {
        return $this->driver->openAdvanced($this->collection);
    }

    /**
     * Shortcut to set the timecode by string.
     */
    public function getFrameFromString(string $timecode): self
    {
        return $this->getFrameFromTimecode(TimeCode::fromString($timecode));
    }

	/**
	 * Shortcut to set the timecode by seconds.
	 * @param float $seconds
	 * @return MediaOpener
	 */
    public function getFrameFromSeconds(float $seconds): self
    {
        return $this->getFrameFromTimecode(TimeCode::fromSeconds($seconds));
    }

	/**
	 * @param TimeCode $timecode
	 * @return $this
	 */
    public function getFrameFromTimecode(TimeCode $timecode): self
    {
        $this->timecode = $timecode;

        return $this;
    }

    /**
     * Returns an instance of MediaExporter with the driver and timecode (if set).
     */
    public function export(): MediaExporter
    {
        return tap(new MediaExporter($this->getDriver()), function (MediaExporter $mediaExporter) {
            if ($this->timecode) {
                $mediaExporter->frame($this->timecode);
            }
        });
    }

    /**
     * Returns an instance of HLSExporter with the driver forced to AdvancedMedia.
     */
    public function exportForHLS(): HLSExporter
    {
        return new HLSExporter($this->getAdvancedDriver());
    }

    public function cleanupTemporaryFiles(): self
    {
        app(TemporaryDirectories::class)->deleteAll();

        return $this;
    }

	/**
	 * @param $items
	 * @param callable $callback
	 * @return $this
	 */
    public function each($items, callable $callback): self
    {
        Collection::make($items)->each(fn($item, $key) => $callback($this->clone(), $item, $key));

        return $this;
    }

    /**
     * Returns the Media object from the driver.
     */
    public function __invoke(): AbstractMediaType
    {
        return $this->getDriver()->get();
    }

	/**
	 * Forwards all calls to the underlying driver.
	 * @param $method
	 * @param $arguments
	 * @return void
	 */
    public function __call($method, $arguments)
    {
        $result = $this->forwardCallTo($driver = $this->getDriver(), $method, $arguments);

        return ($result === $driver) ? $this : $result;
    }
}
