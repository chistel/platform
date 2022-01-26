<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           ResourceNotFound.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 8:36 AM
 */

namespace Platform\Exceptions\Kessel;

use GuzzleHttp\Exception\ClientException;

/**
 * @codeCoverageIgnore
 */
class ResourceNotFound extends Exception
{
    /**
     * @param ClientException $exception
     */
    public function __construct(ClientException $exception)
    {
        parent::__construct('Requested resource not found', 404, $exception);
    }
}