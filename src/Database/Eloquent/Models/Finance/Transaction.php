<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Transaction.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/12/2021, 9:04 AM
 */

namespace App\Models\Finance;


use App\Abstracts\BaseModel;
use Plank\Metable\Metable;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends BaseModel
{
	use Metable;

	/**
	 * @var string
	 */
	protected $table = 'transactions';

	/**
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 * @var array
	 */
	protected $dates = [
		'created_at',
		'updated_at',
		'transaction_date',
	];

	/**
	 * @var array
	 */
	protected $fillable = [
		'ownerable_type',
		'ownerable_id',
		'transactionable_type',
		'transactionable_id',
		//'transaction_date',
		'notes',
		//'transaction_number',
		'reference',
		'amount',
		'status',
		'type',
	];

	/**
	 *
	 */
	protected static function boot()
	{
		parent::boot();

		static::saving(function (self $model) {
			if (is_null($model->reference)) {
				$model->transaction_number = self::getNextTransactionNumber();
			}
		});
	}

	/**
	 * @return string
	 */
	public static function getNextTransactionNumber()
	{
		// Get the last created order
		$transaction = Transaction::orderBy('created_at', 'desc')->first();
		if (!$transaction) {
			// We get here if there is no order at all
			// If there is no number set it to 0, which will be 1 at the end.
			$number = 0;
		} else {
			$number = explode("-", $transaction->number);
			$number = (isset($number[1]) ? $number[1] : $number[0]);
		}
		// If we have ORD000001 in the database then we only want the number
		// So the substr returns this 000001

		// Add the string in front and higher up the number.
		// the %05d part makes sure that there are always 6 numbers in the string.
		// so it adds the missing zero's when needed.

		return sprintf('%06d', intval($number) + 1);
	}

	/**
	 * @return MorphTo
	 */
	public function ownerable()
	{
		return $this->morphTo();
	}

	/**
	 * @return MorphTo
	 */
	public function transactionable()
	{
		return $this->morphTo();
	}

}
