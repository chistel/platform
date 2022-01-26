<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           CannotRenderTemplateMailable.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 2:36 PM
 */

namespace Platform\Exceptions\Mail;

use Exception;
use Platform\Mail\TemplateMailable;

class CannotRenderTemplateMailable extends Exception
{
    public static function layoutDoesNotContainABodyPlaceHolder(TemplateMailable $templateMailable): static
    {
        $mailableClass = class_basename($templateMailable);

        return new static("The layout for mailable `{$mailableClass}` does not contain a `{{{ body }}}` placeholder");
    }
}