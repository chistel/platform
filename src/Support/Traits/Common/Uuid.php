<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Uuid.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 10:11 PM
 */

namespace Platform\Support\Traits\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait Uuid
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected string $uuidField = 'uuid';

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(static function (Model $model) {
            if (Schema::hasColumn($model->getTable(), $model->uuidField) && empty($model->{$uuidField = $model->uuidField})) {
                $model->{$uuidField} = Str::uuid()->toString();
            }
        });
    }

    /**
     * Get the uuidField for the model.
     *
     * @return string
     */
    public function getUuidField(): string
    {
        return $this->uuidField;
    }

    /**
     * Set the uuid field for the model.
     *
     * @param  string  $key
     * @return $this
     */
    public function setUuidField(string $key): static
    {
        $this->uuidField = $key;

        return $this;
    }
}