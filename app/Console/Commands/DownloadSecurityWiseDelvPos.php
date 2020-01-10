<?php

namespace App\Console\Commands;

use App\Http\Controllers\MailController;
use App\Http\Controllers\Utils\Logger;
use App\ModelBhavCopyDelvPosition;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;


class DownloadSecurityWiseDelvPos extends Command
{

    //TODO -> url : https://www.nseindia.com/archives/equities/mto/MTO_04122019.DAT

    private $MAX_DAYS = 5;
    protected $signature = 'download:delv_wise_positions {from_date?} {max_days?}';
    protected $description = 'Download security wise delivery position in cash market';
    public function __construct(){
        parent::__construct();
    }


    public function handle(){
        $from_date = $this->argument('from_date');
        $max_days = $this->argument('max_days');

        if (trim($max_days) == '') $max_days = $this->MAX_DAYS;

        if (trim($from_date) == '') {
            $from_date = Carbon::now()->subDays($max_days);
        }else {
            $from_date = Carbon::createFromFormat('d-m-Y', $from_date);
        }

        for($i = 0; $i < $max_days; $i++){
            $date = $from_date->addDay();
            $this->start_download($date);
        }

        // $mail = new MailController();
        // $msg = "Successfully imported security wise delivery positions for date : " .  Carbon::now()->format('d-m-Y');
        // $mail->send_basic_email(['msg' => $msg], 'Security Wise Delivery Positions copy added');
    }

    private function start_download(Carbon $date){
        $this->info('Trying to download for date : ' . $date->format('d-m-Y'));

        $flag = $this->check_already_imported($date);
        if (!$flag) return false;

        $filename = "MTO_" . $date->format('dmY') . ".DAT";
        $url = "https://www1.nseindia.com/archives/equities/mto/". $filename;
        $this->info("url : " . $url);
        $filepath = $this->download_file($url);
        if (!$filepath) return false;

        $this->import_to_database($filepath, $date->format('Y-m-d'));
    }


    private function check_already_imported(Carbon $date){
        $count = DB::table('bhavcopy_delv_position')->where('date', $date->format('Y-m-d'))->count();
//        $count = ModelBhavCopyDelvPosition::where('date', $date->format('Y-m-d'))->count();
        if ($count == 0) return true;
        $this->info('Already imported data for this date : ' . $date->format('d-m-Y'));
        return false;
    }


    private function import_to_database($filepath, $formatted_date){
        $this->info('Importing records...');

        $storage_path = storage_path('app/'. $filepath);
        $lines = explode("\n", file_get_contents($storage_path));

        $count = 0;
        foreach ($lines as $l){
            $str = explode(',', $l);
            if (count($str) === 7){
                $model = new ModelBhavCopyDelvPosition();
                $model->symbol = $str[2];
                $model->series = $str[3];
                $model->traded_qty = $str[4];
                $model->dlv_qty = $str[5];
                $model->pct_dlv_traded = $str[6];
                $model->date = $formatted_date;
                $model->save();
                $count++;
            }
        }

        $logger = new Logger();
        $msg = "Successfully imported $count records...";
        $logger->insertLog(Logger::LOG_TYPE_DLV_COPY_ADDED, $msg);
        $this->info($msg);
    }


    private function download_file($url){
        $this->info('Downloading file...');
        $client = new Client();
        try{
            $response = $client->request('GET', $url, ['verify' => false]);
            $data = $response->getBody()->getContents();
            $temp_foldername = 'temp/' . Uuid::uuid1()->toString();
            $filename = Uuid::uuid1()->toString() . '.DAT';
            $path = $temp_foldername . '/' . $filename;
            Storage::put($path, $data);
            $this->info('Downloaded file...');
            return $path;
        }catch (Exception $e){
            $this->error('Download error...');
            return null;
        }
    }
}
