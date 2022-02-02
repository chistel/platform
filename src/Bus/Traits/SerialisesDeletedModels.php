<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           SerialisesDeletedModels.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 10:11 PM
 */

namespace Platform\Bus\Traits;

use Illuminate\Contracts\Database\ModelIdentifier;
use Illuminate\Database\Eloquent\SoftDeletes;

trait SerialisesDeletedModels
{
    /**
     * Get the restored property value after deserialization.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function getRestoredPropertyValue($value)
    {
        if (!$value instanceof ModelIdentifier) {
            return $value;
        }

        $model = new $value->class();

        if (uses_trait($model, SoftDeletes::class)) {
            return $model->withTrashed()->findOrFail($value->id);
        }

        return $model->findOrFail($value->id);
    }
}
