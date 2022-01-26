<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Token.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:20 PM
 */

namespace Platform\Database\Eloquent\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Platform\Traits\Common\Uuid;

class Token extends Model
{
    use Uuid;
   /**
    * @var string
    */
   protected $table = 'tokens';

   /**
    * @var string
    */
   protected $primaryKey = 'id';

   /**
    * @var array
    */
   protected $fillable = [
      'type',
      'token',
      'source'
   ];

   /**
    * @return MorphTo
    */
   public function tokenable(): MorphTo
   {
      return $this->morphTo();
   }


   /**
    * @return bool
    */
   public function tokenExpired(): bool
   {
      if ($this->created_at->addHours(72)->isPast()) {
         return true;
      }
      return false;
   }

}
