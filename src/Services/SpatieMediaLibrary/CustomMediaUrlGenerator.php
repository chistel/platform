<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           CustomMediaUrlGenerator.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Services\SpatieMediaLibrary;

use DateTimeInterface;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Filesystem\FilesystemManager;
use Spatie\MediaLibrary\Support\UrlGenerator\BaseUrlGenerator;

/**
 * Class CustomS3MediaUrlGenerator
 * @package Platform\Services\SpatieMediaLibrary
 */
class CustomMediaUrlGenerator extends BaseUrlGenerator
{
	/** @var FilesystemManager */
	protected FilesystemManager $filesystemManager;

	public function __construct(Config $config, FilesystemManager $filesystemManager)
	{
		$this->filesystemManager = $filesystemManager;

		parent::__construct($config);
	}

	/**
	 * Get the url for a media item.
	 *
	 * @return string
	 */
	public function getUrl(): string
	{
		$url = $this->getPathRelativeToRoot();

		if ($root = config('filesystems.disks.'.$this->media->disk.'.root')) {
			$url = $root.'/'.$url;
		}



		$url = $this->versionUrl($url);
		$domain = config('filesystems.disks.'.$this->media->disk.'.url');

		return $domain . '/'. $url;
	}

	/**
	 * Get the temporary url for a media item.
	 *
	 * @param \DateTimeInterface $expiration
	 * @param array $options
	 *
	 * @return string
	 */
	public function getTemporaryUrl(DateTimeInterface $expiration, array $options = []): string
	{
		return $this
			->filesystemManager
			->disk($this->media->disk)
			->temporaryUrl($this->getPath(), $expiration, $options);
	}

	/**
	 * Get the url for the profile of a media item.
	 *
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->getPathRelativeToRoot();
	}

	/**
	 * Get the url to the directory containing responsive images.
	 *
	 * @return string
	 */
	public function getResponsiveImagesDirectoryUrl(): string
	{
		$url = $this->pathGenerator->getPathForResponsiveImages($this->media);

		if ($root = config('filesystems.disks.'.$this->media->disk.'.root')) {
			$url = $root.'/'.$url;
		}

		$domain = config('filesystems.disks.'.$this->media->disk.'.url');

		return $domain . '/' . $url;
	}
}
