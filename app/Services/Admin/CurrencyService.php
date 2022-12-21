<?php

namespace App\Services\Admin;

use App\Models\Currency;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use phpDocumentor\Reflection\Types\Integer;

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
	 * @throws Exception
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

	public function getCurrencyRateFromDB(Carbon $date, string $code): float|bool|int|null
	{
		if (!in_array($code, $this->codes, true)) {
			return false;
		}

		$currencyCollection = Currency::where('code', $code)
			->where('enabled_at', $date->format('Y-m-d'))
			->get();

		if ($currencyCollection === null) {
			throw new Exception('There is no currency exchange rate according to the set parameters');
		}

		$currencyCollection->pluck('rate')
			->toArray()[0];

		return $currencyCollection / $this->ratio;
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
