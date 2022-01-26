<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Bank.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:55 PM
 */

namespace Platform\Database\Eloquent\Models\Common;


use platform\Abstracts\BaseModel;

class Bank extends BaseModel
{
   protected $table = 'banks';

   protected $primaryKey = 'id';

   protected $fillable = [
     'name',
     'code',
     'status'
   ];
}
