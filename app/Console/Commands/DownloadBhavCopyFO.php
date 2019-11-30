<?php

namespace App\Console\Commands;

use App\ExcelModels\ExcelModelBhavCopyFO;
use App\ModelBhavCopyFO;
use Chumper\Zipper\Zipper;
use Exception;
use Faker\Provider\Uuid;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;


class DownloadBhavCopyFO extends Command
{

    //TODO -> url : https://www.nseindia.com/content/historical/DERIVATIVES/2019/NOV/fo27NOV2019bhav.csv.zip


    protected $signature = 'download:bhavcopy_fo';
    protected $description = 'Download bhavcopy future market from NSE website.';
    public function __construct(){
        parent::__construct();
    }


    public function handle(){

        $date = Carbon::now();
        $this->info('Trying to download for date : ' . $date->format('d-m-Y'));

        $year = $date->year;
        $month = strtoupper($date->formatLocalized('%b'));
        $day = $date->day;

        $filename = "fo". $day . $month . $year . "bhav.csv";


        $url = "https://www.nseindia.com/content/historical/DERIVATIVES/$year/$month/" . $filename . '.zip';
        $this->info("url : " . $url);

        $flag = $this->check_already_imported($date);
        if (!$flag) return false;

        $filepath = $this->download_bhav_copy($url);
        if (!$filepath) return false;

        $filepath = $this->extract_zip($filepath);
        $this->import_to_database($filepath, $filename);
    }


    private function check_already_imported(Carbon $date){
        $count = ModelBhavCopyFO::where('date', $date->format('Y-m-d'))->count();
        if ($count == 0) return true;
        $this->info('Already imported data for this date : ' . $date->format('d-m-Y'));
        return false;
    }


    private function import_to_database($filepath, $filename){
        $this->info('Importing records...');
        $this->output->title('Starting import');
        (new ExcelModelBhavCopyFO())->withOutput($this->output)->import("$filepath/$filename");
        $this->output->success('Import successful');
    }

    private function extract_zip($filepath){
        $this->info('Extracting bhavcopy...');
        $zipper = new Zipper();

        $temp_filepath = 'temp/' . Uuid::uuid();

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
            $temp_foldername = 'temp/' . Uuid::uuid();
            $filename = Uuid::uuid() . '.zip';
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