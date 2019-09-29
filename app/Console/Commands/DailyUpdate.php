<?php

namespace App\Console\Commands;

use App\Http\Controllers\Base\AppConstants;
use App\ModelInstrument;
use App\ModelLog;
use App\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class DailyUpdate extends Command
{
    protected $signature = 'daily:update';
    protected $description = 'Update all instruments';
    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        $user = User::where('id', 1)->first();
        $token = $user->access_token;
        $client = new Client(['base_uri' => AppConstants::KITE_HOST, 'timeout'  => 0.0,]);
        try{
            $response = $client->get('/instruments', [
                'headers' => [
                    'Accept' => 'text/csv',
                    'X-Kite-Version' => AppConstants::KITE_VERSION,
                    'Authorization' => 'Token ' . $token,
                ],
            ]);

            $data = $response->getBody()->getContents();
            $lines = explode(PHP_EOL, $data);
            ModelInstrument::query()->truncate();
            for ($i = 1; $i < count($lines) - 1; $i++){
                $array = str_getcsv($lines[$i]);
                $instrument = new ModelInstrument();
                $x = 0;
                $instrument->instrument_token = $array[$x];
                $instrument->exchange_token = $array[++$x];
                $instrument->tradingsymbol = $array[++$x];
                $instrument->name = $array[++$x];
                $instrument->last_price = $array[++$x];
                $instrument->expiry = $array[++$x];
                $instrument->strike = $array[++$x];
                $instrument->tick_size = $array[++$x];
                $instrument->lot_size = $array[++$x];
                $instrument->instrument_type = $array[++$x];
                $instrument->segment = $array[++$x];
                $instrument->exchange = $array[++$x];
                $instrument->save();
            }
            $count = ModelInstrument::count();
            $this->info($count . ' instruments added');
            $model_log = new ModelLog();
            $model_log->log_type = "CronDailyInstrumentAdd";
            $model_log->msg = $count . ' instruments added';
            $model_log->save();
        }catch (Exception $e){
            $this->error($e->getMessage());
            $model_log = new ModelLog();
            $model_log->log_type = "ErrorCronDailyInstrumentAdd";
            $model_log->msg = $e->getMessage();
            $model_log->save();
        }
    }
}
