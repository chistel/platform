<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           BelongsToTransaction.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:49 PM
 */

namespace Platform\Traits\Finance;


use Platform\Database\Eloquent\Models\Finance\Transaction;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait BelongsToTransaction
{
   /**
    * @return MorphOne
    */
   public function transaction(): MorphOne
   {
      return $this->morphOne(Transaction::class, 'transactionable');
   }
}
