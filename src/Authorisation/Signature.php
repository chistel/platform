<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Signature.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Authorisation;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Signature
{
    /**
     * @var array
     */
    protected array $parts;

    /**
     * @param string $app
     * @param string[] $parts
     * @throws AssertionFailedException
     */
    public function __construct(string $app, string ...$parts)
    {
        Assertion::inArray($app, (array)implode(',', config('core.white_label_applications')));

        $this->parts = array_merge([$app], $parts);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return implode(':', array_filter($this->parts));
    }
}
