<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           StateRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

namespace Platform\Repositories\Common\Localisation;

use Platform\Eloquent\Repository;
use Platform\Database\Eloquent\Models\Location\State;

/**
 * Class StateRepository
 * @package Platform\Repositories\Location
 */
class StateRepository extends Repository
{
   /**
    * @return string
    */
   public function model()
   {
      return State::class;
   }
}
