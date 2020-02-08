<?php

namespace App\Console\Commands;

use App\ExcelModels\ExcelModelVix;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;


class ImportVixData extends Command
{

    protected $signature = 'import:vix_data';
    protected $description = 'Import vix data to database.';

    public function __construct(){
        parent::__construct();
    }


    public function handle(){
        $files = Storage::disk('local')->files('public/vix_data');
        foreach ($files as $f){
            $extension = substr($f,strlen($f) - 3,strlen($f));
            if ($extension === 'csv'){
                $this->info('importing : ' . $f);
                (new ExcelModelVix())->withOutput($this->output)->import($f);
            }
        }
        $this->output->success('successfully imported...');
    }




}
