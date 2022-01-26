<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           WithdrawalSetupRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

namespace Platform\Repositories\Finance;

use Platform\Eloquent\Repository;
use Platform\Database\Eloquent\Models\Finance\WithdrawalSetup;

/**
 * Class WithdrawalSetupRepository
 * @package Platform\Repositories\Finance
 */
class WithdrawalSetupRepository extends Repository
{
   /**
    * @return string
    */
   public function model()
   {
      return WithdrawalSetup::class;
   }
}
