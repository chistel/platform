<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Endpoint.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 8:36 AM
 */

namespace Platform\Kessel\Contracts;

interface Endpoint
{
    /**
     * Return endpoint value
     *
     * @return string
     */
    public function value();
}