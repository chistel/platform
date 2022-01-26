<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           SanitizeEmail.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 8:36 AM
 */

namespace Platform\Traits\Common;

use Illuminate\Support\Arr;

trait SanitizeEmail
{
    /**
     * Sanitize email by removing any whitespace
     *
     * @param       $field
     * @param array $input
     *
     * @return array
     */
    public function sanitizeEmail($field, array $input): array
    {
        $input[$field] = preg_replace('/\s+/', '', Arr::get($input, $field));

        $this->replace($input);

        return $input;
    }
}