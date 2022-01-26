<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           MissingChunkFilesException.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/12/2021, 9:01 AM
 */

namespace Platform\Exceptions;

use Throwable;

class MissingChunkFilesException extends \Exception
{
	public function __construct(
		$message = 'Logic did not find any chunk file - check the folder configuration',
		$code = 500,
		Throwable $previous = null
	) {
		parent::__construct($message, $code, $previous);
	}
}
