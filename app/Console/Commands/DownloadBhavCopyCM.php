<?php

namespace App\Console\Commands;

use App\ExcelModels\ExcelModelBhavCopyCM;
use App\Http\Controllers\Utils\Logger;
use App\ModelLog;
use App\ModelMasterStocksFO;
use Chumper\Zipper\Zipper;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;


class DownloadBhavCopyCM extends Command
{
    //url : https://www1.nseindia.com/content/historical/EQUITIES/2019/NOV/cm27NOV2019bhav.csv.zip

    // from_date -> dd-mm-yyyy;
    // max_days -> max_days from from_date;

    private $MAX_DAYS = 20;


    /*
     * modes :
     * fo -> will download only master fo stocks
     * all -> all stocks will be fetched and stored.
     * */
    protected $signature = 'download:bhavcopy_cm {from_date?} {max_days?} {--overwrite=no} {--mode=fo}';
    protected $description = 'Download bhavcopy cash market from NSE website.';
    protected $mode;

    public function __construct(){
        parent::__construct();
    }


    public function handle(){
        $from_date = $this->argument('from_date');
        $max_days = $this->argument('max_days');
        $overwrite = $this->option('overwrite');
        $this->mode = $this->option('mode');


        if (trim($max_days) == '') $max_days = $this->MAX_DAYS;

        if (trim($from_date) == '') {
            $from_date = Carbon::now()->subDays($max_days);
        }else {
            $from_date = Carbon::createFromFormat('d-m-Y', $from_date);
        }

        for($i = 0; $i < $max_days; $i++){
            $date = $from_date->addDay();
            if ($overwrite == 'yes'){
                $this->info('deleting all records for date : ' . $date->format('d-m-Y'));
                DB::table('bhavcopy_cm')->whereDate('date', $date)->delete();
            }
            $this->start_download($date);
        }

    }

    public function start_download(Carbon $date){
        try{

            $flag = $this->check_already_imported($date);
            if (!$flag) return false;


            $this->info('Trying to download for date : ' . $date->format('d-m-Y'));
            $year = $date->year;
            $month = strtoupper($date->formatLocalized('%b'));
            $day = ($date->day < 10) ? ("0" . $date->day) : $date->day;
            $filename = "cm". $day . $month . $year . "bhav.csv";

            $url = "https://www1.nseindia.com/content/historical/EQUITIES/$year/$month/" . $filename . '.zip';
            $this->info("url : " . $url);
            $filepath = $this->download_bhav_copy($url, $date);
            if (!$filepath) return false;

            $filepath = $this->extract_zip($filepath);
            $this->import_to_database($filepath, $filename);
        }catch(Exception $e){
            $this->error('Error : ' . $e->getMessage());
        }
    }

    private function check_already_imported(Carbon $date){
        $count = DB::table('bhavcopy_cm')->where('date', $date->format('Y-m-d'))->count();
        if ($count == 0) return true;
        $this->info('Already imported data for this date : ' . $date->format('d-m-Y'));
        return false;
    }


    private function import_to_database($filepath, $filename){
        $this->info('Importing records...');
        $this->output->title('Starting import');

        $fo_stocks = ModelMasterStocksFO::select('symbol')->get()->pluck('symbol')->toArray();
        (new ExcelModelBhavCopyCM($this->mode, $fo_stocks))->withOutput($this->output)->import("$filepath/$filename");

        $logger = new Logger();
        $msg = "Successfully imported CM records...";
        $logger->insertLog(Logger::LOG_TYPE_CM_COPY_ADDED, $msg);
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
        $referer_url = "https://www1.nseindia.com/ArchieveSearch?h_filetype=eqbhav&date=$formatted_date&section=EQ";

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
            $response = $client->request('GET', $url, [
                'verify' => false,
            ]);
            $data = $response->getBody()->getContents();
            $temp_foldername = 'temp/' . Uuid::uuid1()->toString();
            $filename = Uuid::uuid1()->toString() . '.zip';
            $path = $temp_foldername . '/' . $filename;
            Storage::put($path, $data);
            $this->info('Downloaded bhavcopy...');
            return $path;
        }catch (Exception $e){
            $this->error('Download error...');
            return null;
        }
    }

    private function write_download_error_log(Carbon $date){
        $log = new ModelLog();
        $log->log_type = 'CM_MARKET_DATE_DOWNLOAD_ERROR';
        $log->added_by = 1;
        $log->msg = $date->format('d-m-Y');
        $log->save();
    }
}
