<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           BaseModel.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 10:07 PM
 */

namespace Platform\Abstracts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Schema;
use Plank\Metable\Metable;
use Platform\Database\Eloquent\Models\Common\Token;
use Platform\Support\Traits\Common\CamelCasing;

abstract class BaseModel extends Model
{
    use CamelCasing;
    use HasFactory;
    use Metable;

    /**
     * @return MorphMany
     */
    public function tokens(): MorphMany
    {
        return $this->morphMany(Token::class, 'tokenable');
    }

    /**
     * @return array
     */
    protected function columns(): array
    {
        return Schema::getColumnListing($this->getTable());
    }

    /**
     * @param $query
     * @param array $value
     * @return mixed
     */
    public function scopeExclude($query, array $value = []): mixed
    {
        return $query->select(array_diff($this->columns(), $value));
    }
}
