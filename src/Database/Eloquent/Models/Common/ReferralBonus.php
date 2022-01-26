<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           ReferralBonus.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:24 PM
 */

namespace Platform\Database\Eloquent\Models\Common;

use Platform\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ReferralBonus
 * @package Platform\Models\Common
 */
class ReferralBonus extends BaseModel
{
   /**
    * @var string
    */
   protected $table = 'referral_bonuses';
   /**
    * @var string[]
    */
   protected $fillable = [
      'referral_id',
      'sourceable_type',
      'sourceable_id',
      'amount',
   ];
   /**
    * @var string[]
    */
   protected $casts = [
      'for_date' => 'datetime'
   ];

   /**
    * @return BelongsTo
    */
   public function referral()
   {
      return $this->belongsTo(Referral::class, 'main_referral_id', 'id');
   }
}
