<?php

namespace App\Services\Admin;

use App\Models\Currency;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CurrencyService
{

    public $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange',
            'timeout'  => 2.0,
            'verify'   => false, //https
        ]);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getNewCurrencies()
    {
        $currencies = config('currency_rates.currency_list');
        $today = date("Ymd");
        foreach ($currencies as $currency) {
            $uri = '?valcode='.$currency.'&date='.$today.'&json';
            $response = $this->client->request('GET', $uri);
            if ($response->getStatusCode() !== 200) {
                throw new Exception('There is a probkem with currency rate service');
            }
            $rates = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

            if (!isset($rates[$currency->code])) {
                throw new Exception('There is a probkem with currency '. $currency->code);
            }

            $currency->update(['rate' => $rates[$currency->code]]);

//            return Currency::firstOrCreate(
//                [
//                    'code'       => $rates->code,
//                    'rate'       => $rates->rate * config('currency_rates.exchange_ratio'),
//                    'created_at' => $rates->enable_at,
//                ]
//            );
        }
    }

    public function getCurentCurrency()
    {
        //ToDo
    }
}
