<?php

namespace App\Console\Commands;

use App\Services\Admin\CurrencyService;
use Illuminate\Console\Command;

class ImportJsonPlaceholderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:jsonplaceholder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data from jsonplaceholder';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $import = new CurrencyService();
        $response = $import->client->request('GET', '');
        $datas = json_decode($response->getBody()->getContents());
        foreach ($datas as $data) {
            if ($data->r030 === 840) {
                echo $data->rate.'<br>';
            }
        }
        echo 'final';
        //return Command::SUCCESS;
    }
}
