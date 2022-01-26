<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           ContentRangeValueToLargeException.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/12/2021, 9:01 AM
 */

namespace Platform\Exceptions;

use Exception;

class ContentRangeValueToLargeException extends \Exception
{
	public function __construct(
		$message = 'The content range value is to large',
		$code = 500,
		Exception $previous = null
	) {
		parent::__construct($message, $code, $previous);
	}
}
