<?php

namespace App\Console\Commands;

use App\ExcelModels\ExcelModelBhavCopyCombined;
use App\ModelBhavCopyCombined;
use Chumper\Zipper\Zipper;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;


class DownloadBhavCopyCombined extends Command
{
    private $MAX_DAYS = 1;
    protected $signature = 'download:bhavcopy_combined {from_date?} {max_days?}';
    protected $description = 'Download bhavcopy combined from NSE website.';

    protected $client;

    public function __construct(){
        parent::__construct();
        $this->client = new Client();
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
            $date = $from_date;
            //$this->info($date);
            $this->start_download($date);
            $date = $from_date->addDay();
        }
    }

    private function start_download($date){
        $filename = 'combined_report' . $date->format('dmY');
        $url = "https://www1.nseindia.com/archives/combine_report/$filename.zip";
        $client = $this->client;

        try{
            $response = $client->get($url, []);
            $data = $response->getBody()->getContents();
            Storage::put('temp/bhavcopy.zip', $data);
            $this->extract_zip();
            $filename = $this->get_file_name($filename);
            $this->import_to_database($filename);
            $this->delete_temp();
        }catch (Exception $e){
            $this->error('Error : ' . $e->getMessage());
            return false;
        }
    }

    private function get_file_name($filename){
        $filepath = "temp/bhavcopy_extracted/$filename";
        if (Storage::exists($filepath . '.xlsx')){
            return $filename . '.xlsx';
        }elseif (Storage::exists($filepath . '.xls')){
            return $filename . '.xls';
        }
    }

    private function import_to_database($filename){
        ModelBhavCopyCombined::truncate();
        $this->output->title('Starting import');
        (new ExcelModelBhavCopyCombined)->withOutput($this->output)->import("temp/bhavcopy_extracted/$filename");
        $this->output->success('Import successful');
    }

    private function extract_zip(){
        $zipper = new Zipper();
        $zipper->make(storage_path('app/temp/bhavcopy.zip'))->extractTo(storage_path('app/temp/bhavcopy_extracted'));
    }

    private function delete_temp(){
        Storage::deleteDirectory('temp');
        $this->info('Cleaned temp files...');
    }
}
