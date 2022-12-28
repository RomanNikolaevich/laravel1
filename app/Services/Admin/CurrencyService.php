<?php

namespace App\Services\Admin;

use App\Models\Currency;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

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
     * Entering exchange rates into our database from an external source
     *
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
     * Getting the exchange rate for a specified date from our database
     *
     * @param Carbon $date
     * @param string $code
     * @return float|bool|int|null
     */
    public function getCurrencyRateFromDB(Carbon $date, string $code): float|bool|int|null
    {
        $currencyCollection = Currency::where('code', $code)
            ->where('enabled_at', $date->format('Y-m-d'))
            ->first();

        if ($currencyCollection === null) {
            return null;
        }

        $rate = $currencyCollection['rate'] / $this->ratio;

        $secondsToDayEnd = Carbon::tomorrow()->diffInSeconds(Carbon::now());

        return Cache::remember('rate_' . $code, $secondsToDayEnd, function () use ($rate) {
            return $rate;
        });
    }

    /**
     * Calculation of the price of goods in the specified currency with rounding to a significant figure
     *
     * @param Carbon $date
     * @param string $code
     * @param int $id
     * @return float|int
     * @throws RuntimeException
     */
    public function convertPrice(Carbon $date, string $code, int|float $priceInDefaultCurrency): float|int|null
    {
        /** @var string $defaultCode */
        $defaultCode = config('currency.default_code');

        if ($code === $defaultCode) {
            return $priceInDefaultCurrency;
        }

        if (!in_array($code, $this->codes, true)) {
            $code = $defaultCode;
        }

        $rate = $this->getCurrencyRateFromDB($date, $code);

        if (!$rate) {
            return 0;
        }

        //return $priceInDefaultCurrency / $rate;
        return $this->numberToSignificant($priceInDefaultCurrency / $rate) ;
    }

    /**
     * This Client is a Guzzle(PHP HTTP client)
     *
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
     *
     *
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

    /**
     * rounding to significant numbers
     *
     * @param int|float|null $number
     * @param int $precision
     * @return float|int|null
     */
    private function numberToSignificant(int|float|null $number): float|int|null
    {
        if ($number === 0) {
            return null;
        }

        $precision = config('currency.precision');
        $exponent = floor(log10(abs($number)) + 1);
        $significant = round(($number / (10 ** $exponent)) * (10 ** $precision)) / (10 ** $precision);

        return round($significant * (10 ** $exponent), 2);
    }
}
