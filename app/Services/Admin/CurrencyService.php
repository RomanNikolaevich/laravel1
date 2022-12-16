<?php

namespace App\Services\Admin;

use App\Models\Currency;
use GuzzleHttp\Client;

class CurrencyService
{
    public $client;

    public function __construct()
    {
        $this->client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?json',
            // You can set any number of default request options.
            'timeout'  => 2.0,
            'verify'   => false, //https
        ]);
    }

    public function getNewCurrencies()
    {
        $import = new CurrencyService();
        $response = $import->client->request('GET', '');
        $data = json_decode($response->getBody()->getContents());
        foreach ($data as $item) {
            if ($data->r030 === 840) {
                Currency::firstOrCreate(
                    [
                        'cc'   => $item->r030,
                        'rate' => $item->rate,
                        'created_at' => $item->exchangedate,
                    ]
                );
            }
        }
        //echo 'final';
    }

    public function getCurentCurrency()
    {
        //ToDo
    }
}
