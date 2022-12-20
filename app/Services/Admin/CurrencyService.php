<?php

namespace App\Services\Admin;

use App\Models\Currency;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CurrencyService
{
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
	 * @param string $currency
	 * @param Carbon $date
	 * @return object|null
	 * @throws GuzzleException
	 * @throws \JsonException
	 * @throws Exception
	 */
	private function getRatesByApi(string $currency, Carbon $date): ?object
	{
		$client = $this->getClient();

		$urn = '?valcode=' . $currency . '&date=' . $date->format('Ymd') . '&json';
		$response = $client->request('GET', $urn);

		if ($response->getStatusCode() !== 200) {
			throw new Exception('There is a problem with currency rate service');
		}

		$rateCurrency = json_decode(
			$response->getBody()->getContents(),
			false,
			512,
			JSON_THROW_ON_ERROR
		);

		if ($rateCurrency === null) {
			throw new Exception('There is no exchange rate for the specified date: ' . $date);
		}

		return $rateCurrency[0];
	}

	/**
	 * Exchange rates for tomorrow are updated after 16:00
	 * @throws GuzzleException
	 * @throws Exception
	 */
	public function getNewCurrenciesToDB(): void
	{
		$timeZone = config('app.timezone');

		if ((int)(Carbon::now($timeZone)->format('h')) >= 16) {
			$date = Carbon::tomorrow($timeZone);
		} else {
			throw new Exception('The new exchange rate will be later at 16:00 '. $timeZone);
		}

		$currencies = config('currency.codes');

		foreach ($currencies as $currency) {

			if ($this->getCurrencyRateFromDB($date, $currency)) {
				continue;
			}

			$rates = $this->getRatesByApi($currency, $date);

			if (!isset($rates)) {
				throw new Exception('There is a problem with currency ' . $currency);
			}

			$currencyData = [
				'code' => $currency,
				'rate' => (int)($rates->rate * config('currency.ratio')),
				'enabled_at' => $date->format('Y-m-d'),
			];

			Currency::firstOrCreate($currencyData);
		}
	}

	/**
	 * @param Carbon $date
	 * @param string $code
	 * @return float|int|null
	 * @throws Exception
	 */
	public function getCurrencyRateFromDB(Carbon $date, string $code): float|int|null
	{
		$currencyCollection = Currency::where('code', $code)
			->where('enabled_at', $date->format('Y-m-d'))
			->get()
			->pluck('rate')
			->toArray()[0];

		if ($currencyCollection === null) {
			throw new Exception('There is no currency exchange rate according to the set parameters');
		}

		$ratio = config('currency.ratio');

		 return $currencyCollection/$ratio;
	}
}
