<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           ModelWasDeleted.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Events;

use Platform\Abstracts\BaseModel;

class ModelWasDeleted
{
    public BaseModel $model;

    public function __construct(BaseModel $model)
    {
        $this->model = $model;
    }
}
