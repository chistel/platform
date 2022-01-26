<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           LgaRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

namespace Platform\Repositories\Common\Localisation;

use Platform\Eloquent\Repository;
use Platform\Database\Eloquent\Models\Location\Lga;

/**
 * Class LgaRepository
 * @package Platform\Repositories\Common\Localisation
 */
class LgaRepository extends Repository
{
   /**
    * @return string
    */
   public function model()
   {
      return Lga::class;
   }
}
