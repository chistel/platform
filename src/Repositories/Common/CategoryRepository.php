<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           CategoryRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

namespace Platform\Repositories\Common;

use Platform\Eloquent\Repository;
use Platform\Database\Eloquent\Models\Common\Category;

/**
 * Class CategoryRepository
 * @package Platform\Repositories\Common
 */
class CategoryRepository extends Repository
{
   /**
    * @return string
    */
   public function model()
   {
      return Category::class;
      //return 'Platform\Models\Common\Category';
   }
}
