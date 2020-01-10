<?php

namespace App\Console\Commands;

use App\ExcelModels\ExcelModelBhavCopyFO;
use App\Http\Controllers\MailController;
use App\Http\Controllers\Utils\Logger;
use Chumper\Zipper\Zipper;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;


class DownloadBhavCopyFO extends Command
{

    //TODO -> url : https://www.nseindia.com/content/historical/DERIVATIVES/2019/NOV/fo27NOV2019bhav.csv.zip

    private $MAX_DAYS = 5;
    protected $signature = 'download:bhavcopy_fo';
    protected $description = 'Download bhavcopy future market from NSE website.';
    public function __construct(){
        parent::__construct();
    }


    public function handle(){
        for($i = 0; $i < $this->MAX_DAYS; $i++){
            $date = Carbon::now()->subDays($i);
            $this->start_download($date);
        }

        $mail = new MailController();
        $msg = "Successfully imported fno market data for date : " .  Carbon::now()->format('d-m-Y');
        $mail->send_basic_email(['msg' => $msg], 'FNO copy added');
    }

    private function start_download(Carbon $date){
        $flag = $this->check_already_imported($date);
        if (!$flag) return false;

        $this->info('Trying to download for date : ' . $date->format('d-m-Y'));
        $year = $date->year;
        $month = strtoupper($date->formatLocalized('%b'));
        $day = ($date->day < 10) ? ("0" . $date->day) : $date->day;
        $filename = "fo". $day . $month . $year . "bhav.csv";
        $url = "https://www1.nseindia.com/content/historical/DERIVATIVES/$year/$month/" . $filename . '.zip';
        $this->info("url : " . $url);

        $filepath = $this->download_bhav_copy($url);
        if (!$filepath) return false;

        $filepath = $this->extract_zip($filepath);
        $this->import_to_database($filepath, $filename);
    }


    private function check_already_imported(Carbon $date){
        $count = DB::table('bhavcopy_fo')->where('date', $date->format('Y-m-d'))->count();
//        $count = ModelBhavCopyFO::where('date', $date->format('Y-m-d'))->count();
        if ($count == 0) return true;
        $this->info('Already imported data for this date : ' . $date->format('d-m-Y'));
        return false;
    }


    private function import_to_database($filepath, $filename){
        $this->info('Importing records...');
        $this->output->title('Starting import');
        (new ExcelModelBhavCopyFO())->withOutput($this->output)->import("$filepath/$filename");

        $logger = new Logger();
        $msg = "Successfully imported FNO records...";
        $logger->insertLog(Logger::LOG_TYPE_FNO_COPY_ADDED, $msg);
        $this->output->success($msg);
    }

    private function extract_zip($filepath){
        $this->info('Extracting bhavcopy...');
        $zipper = new Zipper();

        try {
            $temp_filepath = 'temp/' . Uuid::uuid1()->toString();
        } catch (Exception $e) {
        }

        $zipper->make(storage_path('app/'. $filepath))->extractTo(storage_path('app/' . $temp_filepath));
        $this->info('Extracted bhavcopy...');
        return $temp_filepath;
    }


    private function download_bhav_copy($url){
        $this->info('Downloading bhavcopy...');
        $client = new Client();
        try{
            $response = $client->request('GET', $url, ['verify' => false]);
            $data = $response->getBody()->getContents();
            $temp_foldername = 'temp/' . Uuid::uuid1()->toString();
            $filename = Uuid::uuid1()->toString() . '.zip';
            $path = $temp_foldername . '/' . $filename;
            Storage::put($path, $data);
            $this->info('Downloaded bhavcopy...');
            return $path;
        }catch (Exception $e){
            $this->error('Download error...' . $e->getMessage());
            $this->info('Aborting...');
            return null;
        }
    }
}
