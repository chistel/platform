<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           FormRequest.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Abstracts\Http;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

abstract class FormRequest extends BaseFormRequest
{

   /**
    * Base Model.
    *
    * @var Model
    */
   protected Model $model;


   public function setModel(Model $model)
   {
      $this->model = $model;
   }

   public function getModel(): Model
   {
      return $this->model;
   }

   /**
    * Determine if the given offset exists.
    *
    * @param string $offset
    * @return bool
    */
   public function offsetExists($offset)
   {
      return Arr::has(
         $this->route() ? $this->all() + $this->route()->parameters() : $this->all(),
         $offset
      );
   }
}
