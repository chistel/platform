<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           MediaLibraryCustomDirectory.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Services\SpatieMediaLibrary;

use Platform\Repositories\Users\UserRepository;
use Platform\Repositories\Users\UserSchoolRepository;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

/**
 * Class MediaLibraryCustomDirectory
 * @package Platform\Services
 */
class MediaLibraryCustomDirectory implements PathGenerator
{
	/**
	 * @var UserRepository
	 */
	private UserRepository $userRepository;

	/**
	 * @var UserSchoolRepository
	 */
	private UserSchoolRepository $userSchoolRepository;

	/**
	 * MediaLibraryCustomDirectory constructor.
	 * @param UserRepository $userRepository
	 * @param UserSchoolRepository $userSchoolRepository
	 */
	public function __construct(UserRepository $userRepository, UserSchoolRepository $userSchoolRepository)
	{
		$this->userRepository = $userRepository;

		$this->userSchoolRepository = $userSchoolRepository;
	}

	public function getPath(Media $media): string
	{
		$corePath = DIRECTORY_SEPARATOR;
		//'storage'.DIRECTORY_SEPARATOR;
		switch ($media->model_type) {
			case 'user':
				return 'users' . DIRECTORY_SEPARATOR . $this->getBasePath($media);
				break;
			case 'material_course':
				return 'material_courses' . DIRECTORY_SEPARATOR . $this->getBasePath($media);
				break;
			case 'user_school':
				return 'user_school' . DIRECTORY_SEPARATOR . $this->getBasePath($media);
				break;
			default:
				return $this->getBasePath($media);
		}
	}

	public function getPathForConversions(Media $media): string
	{
		return $this->getPath($media) . 'thumbnails' . DIRECTORY_SEPARATOR;
	}

	public function getPathForResponsiveImages(Media $media): string
	{
		return $this->getPath($media) . 'responsive-images' . DIRECTORY_SEPARATOR;
	}

	/*
	  * Get a unique base path for the given media.
	  */
	protected function getBasePath(Media $media): string
	{
		return $media->uuid . DIRECTORY_SEPARATOR;
	}
}
