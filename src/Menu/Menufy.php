<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Menufy.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 7:22 PM
 */

namespace Platform\Menu;

use Illuminate\Support\Facades\Facade;

/**
 * @codeCoverageIgnore
 */
class Menufy extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'menufy';
    }
}
