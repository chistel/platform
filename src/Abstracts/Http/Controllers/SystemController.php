<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           SystemController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/12/2021, 9:01 AM
 */

namespace Platform\Abstracts\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Platform\Support\Traits\Common\Relationships;

/**
 * Class SystemController
 * @package Platform\Abstracts\Http\Controllers
 */
class SystemController extends Controller
{
	use Relationships;

    /**
     * @var array|Application|Request|string
     */
    protected $_config;

    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        $this->_config = request('_config');
    }
}
