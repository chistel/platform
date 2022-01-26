<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           BankRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

namespace Platform\Repositories\Common;

use Platform\Eloquent\Repository;
use Platform\Database\Eloquent\Models\Common\Bank;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BankRepository extends Repository
{
   /**
    * @return string
    */
   public function model()
   {
      return Bank::class;
   }


   /**
    * @param $request
    * @return mixed
    */
   public function getBanks($request)
   {
      $request = array_merge([
         'per_page' => 500,
         'use_pagination' => '0',
         'keyword' => '',
         'sort_by' => 'id',
         'sort_order' => 'asc'
      ], $request);
      $banks = $this->model;
      if (!empty($keyword = $request['keyword'])) {
         $banks = $banks->where('name', 'like', '%' . $keyword . '%')
            ->orWhere('code', 'like', '%' . $keyword . '%');
      }
      if (!empty($sortColumn = $request['sort_by'])) {
         $banks = $banks->orderBy($sortColumn, $request['sort_order']);
      }

      if (!empty($use_pagination = $request['use_pagination']) && $use_pagination == '0') {
         return $banks->take($request['per_page'])->get();
      } else {
         return $banks->paginate($request['per_page']);
      }
   }


   /**
    * @param $field
    * @param $value
    * @param bool $returnException
    * @return mixed
    */
   public function getBank($field, $value, $returnException = false)
   {
      $bank = $this->findOneByField($field, $value);
      if (!is_null($bank)) {
         return $bank;
      }
      throw new NotFoundHttpException();
   }
}
