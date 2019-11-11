<?php

namespace App\Console\Commands;

use App\ExcelModels\ExcelModelBhavCopy;
use App\ModelBhavCopy;
use Chumper\Zipper\Zipper;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class DownloadBhavCopy extends Command
{
    protected $signature = 'download:bhavcopy';
    protected $description = 'Download bhavcopy from NSE website.';
    public function __construct(){
        parent::__construct();
    }

    private function get_date($yesterday){
        if (!$yesterday) $yesterday = Carbon::now()->subDay();

        if($yesterday->dayOfWeek == Carbon::SATURDAY || $yesterday->dayOfWeek == Carbon::SUNDAY)
            return $this->get_date($yesterday->subDay());
        else
            return $yesterday;
    }

    private function set_null_date(){
        DB::update('update bhavcopy set expiry_date = ? where expiry_date = ?',[null,'1970-01-01']);
    }

    public function handle(){

        $date = $this->get_date(null);
        $this->info('Trying to download for date : ' . $date->format('d-m-Y'));
        $filename = 'combined_report' . $date->format('dmY');
        $url = "https://www.nseindia.com/archives/combine_report/$filename.zip";
        $flag = $this->download_bhav_copy($url);
        if (!$flag) return false;
        $this->extract_zip();
        $this->import_to_database($filename . '.xlsx');
        $this->delete_temp();
        $this->set_null_date();
    }

    private function import_to_database($filename){
        $this->info('Importing records...');
        ModelBhavCopy::truncate();
        $this->output->title('Starting import');
        (new ExcelModelBhavCopy)->withOutput($this->output)->import("temp/bhavcopy_extracted/$filename");
        $this->output->success('Import successful');
    }

    private function extract_zip(){
        $this->info('Extracting bhavcopy...');
        $zipper = new Zipper();
        $zipper->make(storage_path('app/temp/bhavcopy.zip'))->extractTo(storage_path('app/temp/bhavcopy_extracted'));
        $this->info('Extracted bhavcopy...');
    }

    private function delete_temp(){
        $this->info('Cleaning temp files');
        Storage::deleteDirectory('temp');
        $this->info('All done for now');
    }

    private function download_bhav_copy($url){
        $this->info('Downloading bhavcopy...');
        $client = new Client();
        try{
            $response = $client->get($url, []);
            $data = $response->getBody()->getContents();
            Storage::put('temp/bhavcopy.zip', $data);
            $this->info('Downloaded bhavcopy...');
            return true;
        }catch (Exception $e){
            $this->error('Download error...');
            $this->info('Aborting...');
            return false;
        }
    }
}
