<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           CollectionMixin.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/12/2021, 9:01 AM
 */

namespace Platform\Macros;

use Closure;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
/**
 * Class CollectionMixin
 * @package Platform\Macros
 */
class CollectionMixin
{
    /**
     * @return Closure
     */
    public function paginate(): Closure
	 {
        return function ($perPage = 20, $page = null, $options = []) {
            $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage), $this->count(),
                $perPage,
                $page, $options
            );
        };

    }
}
