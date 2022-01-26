<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           MissingMailTemplate.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 2:36 PM
 */

namespace Platform\Exceptions\Mail;

use Exception;
use Illuminate\Contracts\Mail\Mailable;

class MissingMailTemplate extends Exception
{
    public static function forMailable(Mailable $mailable): static
    {
        $mailableClass = class_basename($mailable);

        return new static("No mail template exists for mailable `{$mailableClass}`.");
    }
}