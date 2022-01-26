<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           MarkdownField.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 8:36 AM
 */

namespace Platform\Support\Markdown;

use Illuminate\Support\HtmlString;

class MarkdownField extends HtmlString
{
    public function __construct($html)
    {
        parent::__construct("\n!<platform-field>" . $html . "</platform-field>\n");
    }
}