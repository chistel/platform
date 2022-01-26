<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           TagRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/12/2021, 9:01 AM
 */

namespace Platform\Repositories\Common;

use Platform\Eloquent\Repository;
use Spatie\Tags\Tag;

/**
 * Class TagRepository
 * @package Platform\Repositories\Common
 */
class TagRepository extends Repository
{
   /**
    * Specify Model class name
    *
    * @return mixed
    */
   public function model()
   {
      return Tag::class;
   }
}
