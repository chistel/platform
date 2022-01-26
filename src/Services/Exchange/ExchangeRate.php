<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           ExchangeRate.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Services\Exchange;

/**
 * Class ExchangeRate
 * @package Platform\Services\Exchange
 */
abstract class ExchangeRate
{
	abstract public function updateRates();
}
