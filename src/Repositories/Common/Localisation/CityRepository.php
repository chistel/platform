<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           CityRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

namespace Platform\Repositories\Common\Localisation;

use Platform\Eloquent\Repository;
use Platform\Database\Eloquent\Models\Location\City;

/**
 * Class CityRepository
 * @package Platform\Repositories\Common\Localisation
 */
class CityRepository extends Repository
{
   /**
    * @return string
    */
   public function model()
   {
      return City::class;
   }
}
