<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           MediaLibraryObserver.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Observers;

use Spatie\MediaLibrary\MediaCollections\Filesystem;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\MediaCollections\Models\Observers\MediaObserver;

class MediaLibraryObserver extends MediaObserver
{
	public function updating(Media $media)
	{
		/** @var Filesystem $filesystem */
		$filesystem = app(Filesystem::class);

		if (config('media-library.moves_media_on_update')) {
			$filesystem->syncMediaPath($media);
		}

		if ($media->file_name !== $media->getOriginal('file_name')) {
			//$filesystem->syncFileNames($media);
		}
	}
}
