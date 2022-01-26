<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           SanitizePhoneNumber.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 8:36 AM
 */

namespace Platform\Traits\Common;

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