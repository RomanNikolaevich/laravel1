<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\CurrencyService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
	/**
	 * @return void
	 * @throws GuzzleException
	 * @throws \JsonException
	 */
	public static function saveRates(): void
	{
		$service = new CurrencyService();
		$service->updateCurrencies(\Carbon\Carbon::now());
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
