<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           UploadMissingFileException.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/12/2021, 9:01 AM
 */

namespace Platform\Exceptions;

use Exception;

class UploadMissingFileException extends \Exception
{
	/**
	 * Construct the exception. Note: The message is NOT binary safe.
	 *
	 * @see  http://php.net/manual/en/exception.construct.php
	 *
	 * @param string $message [optional] The Exception message to throw
	 * @param int $code [optional] The Exception code
	 * @param Exception|null $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
	 *
	 * @since 5.1.0
	 */
	public function __construct($message = 'The request is missing a file', $code = 400, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
