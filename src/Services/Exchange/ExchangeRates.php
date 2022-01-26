<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           ExchangeRates.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Services\Exchange;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Platform\Repositories\Common\CurrencyRepository;
use Prettus\Validator\Exceptions\ValidatorException;
use Platform\Repositories\Common\ExchangeRateRepository;

class ExchangeRates extends ExchangeRate
{
	/**
	 * API endpoint
	 *
	 * @var string
	 */
	protected string $apiEndPoint;
	/**
	 * @var CurrencyRepository
	 */
	protected CurrencyRepository $currencyRepository;
	/**
	 * @var ExchangeRateRepository
	 */
	protected ExchangeRateRepository $exchangeRateRepository;

	/**
	 * ExchangeRates constructor.
	 * @param CurrencyRepository $currencyRepository
	 * @param ExchangeRateRepository $exchangeRateRepository
	 */
	public function __construct(
		CurrencyRepository $currencyRepository,
		ExchangeRateRepository $exchangeRateRepository
	)
	{
		$this->currencyRepository = $currencyRepository;

		$this->exchangeRateRepository = $exchangeRateRepository;

		$this->apiEndPoint = 'https://api.exchangeratesapi.io/latest';
	}

	/**
	 * Fetch rates and updates in currency_exchange_rates table
	 *
	 * @throws GuzzleException
     */
	public function updateRates()
	{
		$client = new \GuzzleHttp\Client();

		foreach ($this->currencyRepository->all() as $currency) {
			if ($currency->code == config('app.currency')) {
				continue;
			}

			$result = $client->request('GET', $this->apiEndPoint . '?base=' . config('app.currency') . '&symbols=' . $currency->code);

			$result = json_decode($result->getBody()->getContents(), true);

			if (isset($result['success']) && !$result['success']) {
				throw new Exception(
					$result['error']['info'] ?? $result['error']['type'], 1);
			}

			if ($exchangeRate = $currency->exchange_rate) {
				$this->exchangeRateRepository->update([
					'rate' => $result['rates'][$currency->code],
				], $exchangeRate->id);
			} else {
				$this->exchangeRateRepository->create([
					'rate' => $result['rates'][$currency->code],
					'target_currency' => $currency->id,
				]);
			}
		}
	}
}
