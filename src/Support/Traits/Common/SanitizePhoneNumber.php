<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           SanitizePhoneNumber.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 10:12 PM
 */

namespace Platform\Support\Traits\Common;

use Illuminate\Support\Arr;

trait SanitizePhoneNumber
{
    /**
     * @param string $field
     * @param array $input
     * @return array
     */
    public function sanitizeMobile($field, array $input)
    {
        $input[$field] = preg_replace('/[^0-9+]/', '', Arr::get($input, $field));

        $this->replace($input);

        return $input;
    }
}