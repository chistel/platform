<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           PaymentProcessorException.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/12/2021, 9:01 AM
 */

namespace Platform\Exceptions;

use Exception;

/**
 * Class PaymentException
 *
 * @package Platform\Exceptions
 */
class PaymentProcessorException extends Exception
{
	/**
	 * @return static
	 */
	public static function invalidTransaction(): self
	{
		return new static('Transaction is invalid');
	}

	/**
	 * @return static
	 */
	public static function paymentUnverified(): self
	{
		return new static('Payment could not be verified');
	}

	/**
	 * @param string $message
	 * @return static
	 */
	public static function transactionExist(string $message = ''): self
	{
		return new static($message ?? 'Payment already exist');
	}

	/**
	 * @param string $message
	 * @return $this
	 */
	public static function unKnownItem(string $message = ''): self
	{
		return new static($message ?? 'We could not determine what you want to pay for');
	}

	/**
	 * @param string $message
	 * @return static
	 */
	public static function noPaymentMethod(string $message = '')
	{
		return new  static($message ?? 'Please select a payment method');
	}
}
