<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           ModelWasCopied.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     02/02/2022, 11:29 AM
 */

namespace Platform\Database\Events;

use Platform\Database\Eloquent\Model;

class ModelWasCopied
{
    /**
     * @var Model
     */
    public $original;

    /**
     * @var Model
     */
    public $copy;

    /**
     * @param Model $original
     * @param Model $copy
     */
    public function __construct(Model $original, Model $copy)
    {
        $this->original = $original;
        $this->copy = $copy;
    }
}
