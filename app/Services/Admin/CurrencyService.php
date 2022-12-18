<?php

namespace App\Services\Admin;

use App\Models\Currency;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;

class CurrencyService
{
    private Client $client;
    private mixed $currencies;
    private Carbon $dateToday;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('currency.api_url'),
            'timeout'  => 2.0,
            'verify'   => false,
        ]);
        $this->dateToday = Carbon::now();
        $this->currencies = config('currency.codes');
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getNewCurrencies()
    {
        //dd(111);
        foreach ($this->currencies as $currency) {
//            if ($this->getCurrency($dateToday, $currency)){
//                continue;
//            }

            $urn = '?valcode='.$currency.'&date='.$this->dateToday->format('Ymd').'&json';
            $response = $this->client->request('GET', $urn);
            //dd(111);
            if ($response->getStatusCode() !== 200) {
                throw new Exception('There is a problem with currency rate service');
            }
            //dd(222);
            $rates = json_decode(
                         $response->getBody()->getContents(),
                         false,
                         512,
                         JSON_THROW_ON_ERROR
                     )[0] ?? [];

            //dd($rates->exchangedate);
//            if (($rates->exchangedate) !== ($this->dateToday->format('Y-m-d')){
//                    return false;
//                }
            if (!isset($rates)) {
                throw new Exception('There is a problem with currency '.$rates->code);
            }
            //dd(111);
//            $currency->update(['rate' => $rates[$currency->code]]);

            $currencyData = [
                'code'       => $rates->code,
                'rate'       => $rates->rate * config('currency.ratio'),
                'created_at' => $this->dateToday->format('Y-m-d'),
            ];

            Currency::firstOrCreate($currencyData);
        }
    }

    public function getCurrency(mixed $date, string $code):mixed
    {
        $result = DB::table('currencies')->get();
        //->where('code', $code and 'enable_at', $date)
        //->where('enable_at', $date)
        //->value('rate');
        //->get();

        $val = config('currency.ratio');
        if (!empty($result)) {
            return $result[0]->rate / $val;
        }

        return 'There is no currency exchange rate according to the set parameters';
    }
}
