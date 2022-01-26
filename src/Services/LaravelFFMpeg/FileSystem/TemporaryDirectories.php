<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           TemporaryDirectories.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Services\LaravelFFMpeg\FileSystem;

use Exception;
use Illuminate\Filesystem\Filesystem;

/**
 * Class TemporaryDirectories
 * @package Platform\Services\LaravelFFMpeg\FileSystem
 */
class TemporaryDirectories
{
	/**
	 * Root of the temporary directories.
	 *
	 * @var string
	 */
	private $root;

	/**
	 * Array of all directories
	 *
	 * @var array
	 */
	private array $directories = [];
	/**
	 * @var Filesystem
	 */
	private Filesystem $fileSystem;

	/**
	 * Sets the root and removes the trailing slash.
	 *
	 * @param string|null $root
	 */
	public function __construct(string $root = null)
	{
		//logger()->info('na root : ' . $root);
		if (is_null($root)) {
			$this->root = rtrim(config('laravel-ffmpeg.temporary_files_root'), '/');
		} else {
			$this->root = rtrim($root, '/');
		}
		$this->fileSystem = new Filesystem;
	}

	/**
	 * Returns the full path a of new temporary directory.
	 *
	 * @return string
	 * @throws Exception
	 */
	public function create(): string
	{
		$directory = '';
		if (!is_null($this->root) && !empty($this->root)) {
			$directory .= $this->root . DIRECTORY_SEPARATOR;
		}
		$directory .= bin2hex(random_bytes(8));

		$this->fileSystem->makeDirectory($directory);

		return $this->directories[] = $directory;
	}

	/**
	 * Loop through all directories and delete them.
	 */
	public function deleteAll(): void
	{
		foreach ($this->directories as $directory) {
			$this->fileSystem->deleteDirectory($directory);
		}

		$this->directories = [];
	}
}
