<?php

namespace App\Jobs;

use App\Services\Admin\CurrencyService;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCurrencyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	private Carbon $data;

	/**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Carbon $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
	{
		$currency = new CurrencyService();
		try {
			$currency->updateCurrencies($this->data);
		} catch (GuzzleException|\JsonException|\Exception $e) {
			throw new Exception('no exchange rate for the specified date '.$this->data);
		}
	}
}
