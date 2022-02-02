<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Model.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     02/02/2022, 11:23 AM
 */

namespace Platform\Database\Eloquent;

use Illuminate\Support\Arr;
use Platform\Database\Events\ModelWasCopied;
use Platform\Database\Events\ModelWasDeleted;
use Platform\Database\Traits\Raiseable;
use Platform\Support\Traits\Common\CamelCasing;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    // Ensure that fields are always approached with camel-casing
    use CamelCasing {
        attributesToArray as camelAttributesToArray;
    }
    use Raiseable;

    /**
     * The following two variables set defaults for when a model object is
     * copied or deleted, and these are easily overloaded for more custom objects.
     *
     * @var string
     */
    protected $copiedEvent = ModelWasCopied::class;
    protected $deletedEvent = ModelWasDeleted::class;

    /**
     * Stores the changed attributes, retrieved from dirty.
     *
     * @var array
     */
    public $changed = [];

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new Collection($models);
    }

    /**
     * Implementation o 5.2's restore for when we upgrade.
     */
    public function restore()
    {
        $this->deletedAt = null;
        $this->save();
    }

    /**
     * Overloads the parent method to set the changed attributes.
     *
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if ($this->isDirty()) {
            $this->changed = $this->getDirty();
        }

        return parent::save($options);
    }


    /**
     * Return the cache key for the model. Taken from basecamp/rails.
     *
     * @return string
     */
    public function cacheKey()
    {
        if ($this->exists) {
            return class_basename($this).'-'.$this->id.'-'.$this->updatedAt;
        }
        return class_basename($this).'-new';
    }

    /**
     * Copies the model into a new model, clearing the unique fields.
     *
     * @param array $except
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function copy(array $except = null)
    {
        $except = $except ?: [
            $this->getKeyName(),
            'slug',
            $this->getCreatedAtColumn(),
            $this->getUpdatedAtColumn(),
        ];

        $copy = $this->replicate($except);
        $copyEvent = $this->copiedEvent;

        $copy->raise(new $copyEvent($this, $copy));

        return $copy;
    }

    /**
     * Overload the delete method so that we can throw more specific event objects.
     *
     * @throws \Exception
     * @return bool|null
     * @codeCoverageIgnore
     */
    public function delete()
    {
        $result = parent::delete();

        if ($result) {
            $deletedEvent = $this->deletedEvent;
            $this->raise(new $deletedEvent($this));
        }

        return $result;
    }

    /**
     * Converts objects with __toString methods to their string representations when
     * fetching all attributes from a model.
     *
     * @return array
     */
    public function attributesToArray()
    {
        return array_map(function ($attribute) {
            if (is_object($attribute) && method_exists($attribute, '__toString')) {
                return $attribute->__toString();
            }
            return $attribute;
        }, self::camelAttributesToArray());
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param null $key
     * @param null $default
     * @return mixed
     */
    public function getOriginal($key = null, $default = null)
    {
        return Arr::get($this->original, $key, $default);
    }
}