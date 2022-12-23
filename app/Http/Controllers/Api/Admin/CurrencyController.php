<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\CurrencyService;
use GuzzleHttp\Exception\GuzzleException;

class CurrencyController extends Controller
{
	/**
	 * @return void
	 * @throws GuzzleException
	 * @throws \JsonException
	 */
	public static function updateRates(): void
	{
		$service = new CurrencyService();
		$service->updateCurrencies(\Carbon\Carbon::tomorrow());
	}

	/**
	 * @return void
	 * @throws \Exception
	 */
	public static function readRate(): void
	{
		$service = new CurrencyService();
		$service->getCurrencyRateFromDB(\Carbon\Carbon::now(), 'EUR');
	}
}
