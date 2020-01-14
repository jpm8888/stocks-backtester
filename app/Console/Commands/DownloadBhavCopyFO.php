<?php

namespace App\Console\Commands;

use App\ExcelModels\ExcelModelBhavCopyFO;
use App\Http\Controllers\Utils\Logger;
use App\ModelLog;
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

    private $MAX_DAYS = 20;
    protected $signature = 'download:bhavcopy_fo {from_date?} {max_days?}';
    protected $description = 'Download bhavcopy future market from NSE website.';

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

//        $mail = new MailController();
//        $msg = "Successfully imported fno market data for date : " .  Carbon::now()->format('d-m-Y');
//        $mail->send_basic_email(['msg' => $msg], 'FNO copy added');
    }

    private function start_download(Carbon $date){
        $flag = $date->isWeekday();
        if (!$flag) return false;

        $flag = $this->check_already_imported($date);
        if (!$flag) return false;

        $this->info('Trying to download for date : ' . $date->format('d-m-Y'));
        $year = $date->year;
        $month = strtoupper($date->formatLocalized('%b'));
        $day = ($date->day < 10) ? ("0" . $date->day) : $date->day;
        $filename = "fo". $day . $month . $year . "bhav.csv";
        $url = "https://www1.nseindia.com/content/historical/DERIVATIVES/$year/$month/" . $filename . '.zip';
        $this->info("url : " . $url);

        $filepath = $this->download_bhav_copy($url, $date);
        if (!$filepath) return false;

        $filepath = $this->extract_zip($filepath);
        $this->import_to_database($filepath, $filename);
    }


    private function check_already_imported(Carbon $date){
        $count = DB::table('bhavcopy_fo')->where('date', $date->format('Y-m-d'))->count();
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
        //$logger->insertLog(Logger::LOG_TYPE_FNO_COPY_ADDED, $msg);
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


    private function download_bhav_copy($url, Carbon $date){
        $formatted_date = $date->format('d-m-Y');
        $referer_url = "https://www1.nseindia.com/ArchieveSearch?h_filetype=fobhav&date=$formatted_date&section=FO";
        $this->info('Referer URL : ' . $referer_url);

        $client = new Client([
            'headers' => [
                'referer' => $referer_url,
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.14; rv:72.0) Gecko/20100101 Firefox/72.0',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Accept-Encoding' => 'gzip, deflate, br',
            ],
        ]);
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
            $this->write_download_error_log($date);
            $this->error('Download error...');
            return null;
        }
    }

    private function write_download_error_log(Carbon $date){
        $log = new ModelLog();
        $log->log_type = 'FNO_MARKET_DATE_DOWNLOAD_ERROR';
        $log->added_by = 1;
        $log->msg = $date->format('d-m-Y');
        $log->save();
    }
}
