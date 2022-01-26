<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           WalletRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

namespace Platform\Repositories\Common;


use Platform\Eloquent\Repository;
use Exception;
use Platform\Database\Eloquent\Models\Common\Wallet;
use Illuminate\Support\Facades\DB;
use Platform\Exceptions\UnacceptedTransactionException;

/**
 * Class WalletRepository
 * @package Platform\Repositories\Common
 */
class WalletRepository extends Repository
{

   /**
    * Specify Model class name
    *
    * @return mixed
    */
   public function model()
   {
      return Wallet::class;
   }

	/**
	 * @param $model
	 * @param $amount
	 * @param array $meta
	 * @param string $transaction_ref
	 * @return bool
	 * @throws Exception
	 */
   public function deposit($model, $amount, $meta = [], $transaction_ref = '')
   {
      $wallet = $this->walletInstanceOrCreate($model);

      try {
         DB::beginTransaction();
         $transaction = $wallet->transaction()
            ->create([
               'ownerable_type' => $model->getMorphClass(),
               'ownerable_id' => $model->getKey(),
               'amount' => $amount,
               'type' => 1,
               'status' => 1,
               'reference' => (isset($transaction_ref) && !is_null($transaction_ref) ? $transaction_ref : '')
            ]);
         $transaction->syncMeta($meta);
         $wallet->balance += $amount;
         $wallet->save();
         DB::commit();
         return true;
      } catch (\Exception $exception) {
         logger()->error('Wallet deposit exception error : ' . $exception);
         DB::rollBack();
         throw new \Exception($exception);
      }
   }


   /**
    * @param $model
    * @param $amount
    * @param array $meta
    * @param string $transaction_ref
    * @return bool
    * @throws Exception
    */
   public function withDraw($model, $amount, $meta = [], $transaction_ref = '')
   {
      $wallet = $this->walletInstanceOrCreate($model);
      if (!$wallet->canWithdraw($amount)) {
         throw new Exception('Insufficient funds in wallet');
      }
      DB::beginTransaction();
      try {
         $transaction = $wallet->transaction()
            ->create([
               'ownerable_type' => $model->getMorphClass(),
               'ownerable_id' => $model->getKey(),
               'amount' => $amount,
               'type' => 0,
               'status' => 1,
               'reference' => (isset($transaction_ref) && !is_null($transaction_ref) ? $transaction_ref : '')
            ]);
         $transaction->syncMeta($meta);
         $wallet->balance -= $amount;
         $wallet->save();
         DB::commit();
         return true;
      } catch (\Exception $exception) {
         logger()->error('Wallet withdrawal exception error : ' . $exception);
         DB::rollBack();
         throw new Exception($exception);
      }
   }

   /**
    * @param $model
    * @return mixed
    */
   public function walletInstanceOrCreate($model)
   {
      return $this->model->firstOrCreate([
         'owner_type' => $model->getMorphClass(),
         'owner_id' => $model->getKey(),
      ]);
   }
}


