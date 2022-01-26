<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Exception.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 8:36 AM
 */

namespace Platform\Exceptions\Kessel;

/**
 * @codeCoverageIgnore
 */
class Exception extends \Exception
{
    /**
     * @return string
     */
    public function responseString(): string
    {
        return $this->getPrevious()->getResponse()->getBody()->getContents();
    }

    /**
     * @return array
     */
    public function responseArray(): array
    {
        return json_decode($this->getPrevious()->getResponse(), true);
    }
}