<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           HasTransactions.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:49 PM
 */

namespace Platform\Traits\Finance;

use Platform\Database\Eloquent\Models\Finance\Transaction;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTransactions
{
   /**
    * Define a polymorphic one-to-many relationship.
    *
    * @param $related
    * @param $name
    * @param null $type
    * @param null $id
    * @param null $localKey
    * @return mixed
    */
   abstract public function morphMany($related, $name, $type = NULL, $id = NULL, $localKey = NULL);

   /**
    * The user may have many transactions.
    *
    * @return MorphMany
    */
   public function transactions(): MorphMany
   {
      return $this->morphMany(Transaction::class, 'ownerable');
   }
}
