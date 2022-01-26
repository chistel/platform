<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           GoogleClientInvalidConfiguration.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/12/2021, 9:01 AM
 */

namespace Platform\Exceptions;

use Exception;

class GoogleClientInvalidConfiguration extends Exception
{
	public static function calendarIdNotSpecified(): static
    {
		return new static('There was no calendar id specified. You must provide a valid calendar id to fetch events for.');
	}

	public static function credentialsJsonDoesNotExist(string $path): static
    {
		return new static("Could not find a credentials file at `{$path}`.");
	}

	public static function credentialsTypeWrong($credentials): static
    {
		return new static(sprintf('Credentials should be an array or the path of json file. "%s was given.', gettype($credentials)));
	}

	public static function invalidAuthenticationProfile($authProfile): static
    {
		return new static("Authentication profile [{$authProfile}] does not match any of the supported authentication types.");
	}
}
