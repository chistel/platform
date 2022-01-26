<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           HasWallet.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

namespace Platform\Traits\Common;

use Platform\Database\Eloquent\Models\Common\Wallet;

trait HasWallet
{
   /**
    * Retrieve the balance of this user's wallet
    * @return mixed
    */
   public function getWalletBalanceAttribute()
   {
      return !is_null($this->wallet) ? $this->wallet->refresh()->balance :0;
   }
   /**
    * Retrieve the balance of this user's wallet
    * @return mixed
    */
   public function getBalanceAttribute()
   {
      return $this->wallet->refresh()->balance;
   }
   /**
    * Retrieve the wallet of this user
    * @return mixed
    */
   public function wallet()
   {
      return $this->morphOne(Wallet::class, 'owner')->withDefault();
   }
}
