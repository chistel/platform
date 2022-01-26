<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           MixinServiceProvider.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Providers;

use Platform\Macros\BuilderMixin;
use Illuminate\Support\Collection;
use Platform\Macros\CollectionMixin;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class MixinServiceProvider extends ServiceProvider
{
    protected array $mixins = [
        Builder::class => BuilderMixin::class,
        Collection::class => CollectionMixin::class
    ];


    public function register()
    {
        foreach ($this->mixins as $class => $mixin) {
            $class::mixin(new $mixin);
        }

        /* if ($this->app->environment('testing')) {
             foreach ($this->testingMixins as $class => $mixin) {
                 $class::mixin(new $mixin);
             }
         }*/
    }
}
