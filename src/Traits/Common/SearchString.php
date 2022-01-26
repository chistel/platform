<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           SearchString.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Traits\Common;

trait SearchString
{
	/**
	 * Get the value of a name in search string
	 * Example: search=type:customer year:2020 account_id:20
	 * Example: issued_at>=2021-02-01 issued_at<=2021-02-10 account_id:49
	 *
	 * @param $name
	 * @param string $default
	 * @param null $input
	 * @return string|array
	 */
	public function getSearchStringValue($name, $default = '', $input = null)
	{
		$value = $default;

		if (is_null($input)) {
			$input = request('search');
		}

		// $manager = $this->getSearchStringManager();
		// $parsed = $manager->parse($input);

		$columns = explode(' ', $input);

		foreach ($columns as $column) {
			$variable = preg_split('/:|>?<?=/', $column);

			if (empty($variable[0]) || ($variable[0] != $name) || empty($variable[1])) {
				continue;
			}

			if (strpos($column, ':')) {
				$value = $variable[1];

				break;
			}

			if (!is_array($value)) {
				$value = [];
			}

			$value[] = $variable[1];
		}

		return $value;
	}
}
