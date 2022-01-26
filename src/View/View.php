<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           View.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 10:52 PM
 */

namespace Platform\View;

use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Support\Arrayable;

abstract class View implements Arrayable
{
    /**
     * Stores the cache to be used for the request lifecycle.
     *
     * @var array
     */
    private $cache = [];

    /**
     * By accessing methods as properties, we can create a request-based cache so that subsequent
     * calls result in the same, previously called result on that method. This is particularly
     * useful for views where variables may be called numerous times.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }
        return $this->cache[$key] = $this->{$key}();
    }

    /**
     * Here we make the methods available as properties to the view. Because the laravel View
     * component cannot be replaced, unfortunately we have to call all methods upfront and
     * then pass all results to Laravel.
     *
     * @return array
     */
    public function toArray()
    {
        $methods = $this->getPublicMethods();

        foreach ($methods as $method) {
            $this->cache[$method] = Arr::get($this->cache, $method, function () use ($method) {
                return $this->__get($method);
            });
        }

        return $this->cache;
    }

    /**
     * Returns the methods that are safe for calling. Ultimately, this is a hack - we will move away from this.
     *
     * @return array
     */
    private function getPublicMethods()
    {
        $safeMethods = ['__get', 'toArray', '__construct', 'getPublicMethods'];
        $reflection = new ReflectionClass($this);

        return array_map(function ($method) {
            return $method->name;
        }, array_filter($reflection->getMethods(ReflectionMethod::IS_PUBLIC), function ($method) use ($safeMethods) {
            return !preg_match("/^set[A-Z].*/", $method->name) && !in_array($method->name, $safeMethods);
        }));
    }
}
