<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           ModelWasUnarchived.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     02/02/2022, 11:29 AM
 */

namespace Platform\Database\Events;

use Platform\Database\Eloquent\Model;

class ModelWasUnarchived
{
    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}
