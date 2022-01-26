<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           CountryRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

namespace Platform\Repositories\Common\Localisation;

use Platform\Eloquent\Repository;
use Platform\Jobs\Common\Localisation\Country\DeleteCountry;
use Platform\Jobs\Common\Localisation\Country\UpdateCountry;
use Platform\Database\Eloquent\Models\Common\Country;
use Platform\Traits\Common\Jobs;

class CountryRepository extends Repository
{
    use Jobs;
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return Country::class;
    }

    public function updateCountry($country, $data)
    {
        return $this->ajaxDispatch(new UpdateCountry($country, $data));
    }

    public function deleteCountry($country)
    {
        return $this->ajaxDispatch(new DeleteCountry($country));
    }
}
