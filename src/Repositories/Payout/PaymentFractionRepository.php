<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           PaymentFractionRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

namespace Platform\Repositories\Payout;

use Platform\Eloquent\Repository;
use Platform\Database\Eloquent\Models\Finance\PaymentFraction;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PaymentFractionRepository
 * @package Platform\Repositories\Payout
 */
class PaymentFractionRepository extends Repository
{
   /**
    * @return string|void
    */
   public function model()
   {
      return PaymentFraction::class;
   }

   public function getFractions()
   {

   }

   /**
    * @param $id
    * @param $status
    */
   public function changeStatus($id, $status)
   {
      $this->model->where('id', $id)->update(['status'=>$status]);
   }

   /**
    * @param $field
    * @param $value
    * @return mixed
    */
   public function getFraction($field, $value)
   {
      $fraction = $this->findOneByField($field, $value);
      if (!is_null($fraction)){
         return $fraction;
      }
      throw new NotFoundHttpException();
   }
}
