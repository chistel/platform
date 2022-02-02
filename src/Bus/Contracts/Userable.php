<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Userable.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 10:11 PM
 */

namespace Platform\Bus\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface Userable
{
    /**
     * Return the user that the command is for.
     *
     * @return Authenticatable
     */
    public function user();
}
