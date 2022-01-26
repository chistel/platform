<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           RecoveryCode.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Services;

use Illuminate\Support\Str;

class RecoveryCode
{
	/**
	 * Generate a new recovery code.
	 *
	 * @return string
	 */
	public static function generate(): string
    {
		return Str::random(10) . '-' . Str::random(10);
	}
}
