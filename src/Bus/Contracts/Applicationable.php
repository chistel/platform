<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Applicationable.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Bus\Contracts;

interface Applicationable
{
    /**
     * Returns the account that this command is for.
     */
    public function application();
}
