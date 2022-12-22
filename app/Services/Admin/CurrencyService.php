<?php

namespace App\Services\Admin;

use App\Models\Currency;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CurrencyService
{
	private array $codes;
	private array|int $ratio;

	public function __construct()
	{
		$this->codes = config('currency.codes');
		$this->ratio = config('currency.ratio');
	}


	/**
	 * @param Carbon $date
	 * @return void
	 * @throws GuzzleException
	 * @throws \JsonException
	 */
	public function updateCurrencies(Carbon $date): void
	{
		$currencies = $this->getRatesByApi($date);

		foreach ($this->codes as $code) {
			foreach ($currencies as $currency) {
				if ($currency['cc'] === $code && !empty($currency['rate'])) {
					$currencyData = [
						'code' => $code,
						'rate' => $currency['rate'] * $this->ratio,
						'enabled_at' => $date->format('Y-m-d'),
					];

					Currency::firstOrCreate($currencyData);
				}
			}
		}
	}

	/**
	 * @param Carbon $date
	 * @param string $code
	 * @return float|bool|int|null
	 * @throws Exception
	 */
	public function getCurrencyRateFromDB(Carbon $date, string $code): float|bool|int|null
	{
		if (!in_array($code, $this->codes, true)) {
			throw new Exception('Wrong currency code');
		}

		$currencyCollection = Currency::where('code', $code)
			->where('enabled_at', $date->format('Y-m-d'))
			->first();

		if ($currencyCollection === null) {
			return null;
		}

		$rate = $currencyCollection['rate']/$this->ratio;

		return $rate;
	}

	public function getCurrencyType(string $type)
	{
		if ($type !== config('currency.codes_main')) {
			//ToDO: сделать перерасчет цены 'price' из таблицы 'products' в валюте $type по курсу из таблицы 'currencies'
		}
	}

	/**
	 * @return Client
	 */
	private function getClient(): Client
	{
		return new Client([
			'base_uri' => config('currency.api_url'),
			'timeout' => 2.0,
			'verify' => false,
		]);
	}


	/**
	 * @param Carbon $date
	 * @return array|null
	 * @throws GuzzleException
	 * @throws \JsonException
	 */
	private function getRatesByApi(Carbon $date): ?array
	{
		$client = $this->getClient();
		$urn = '?&date=' . $date->format('Ymd') . '&json';
		$response = $client->request('GET', $urn);

		if ($response->getStatusCode() !== 200) {
			throw new Exception('There is a problem with currency rate service');
		}

		$rateCurrency = json_decode(
			$response->getBody()->getContents(),
			true,
			512,
			JSON_THROW_ON_ERROR
		);

		if ($rateCurrency === null) {
			throw new Exception('There is no exchange rate for the specified date: ' . $date);
		}

		return $rateCurrency;
	}
}
