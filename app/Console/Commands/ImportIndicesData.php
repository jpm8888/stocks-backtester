<?php

namespace App\Console\Commands;

use App\ExcelModels\ExcelModelIndices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;


class ImportIndicesData extends Command
{

    protected $signature = 'import:indices';
    protected $description = 'Import indices data to database.';

    public function __construct(){
        parent::__construct();
    }


    public function handle(){
        $this->import('NIFTY');
        $this->import('BANKNIFTY');
    }


    private function import($symbol){
        $path = null;
        switch ($symbol){
            case 'NIFTY':
                $path = 'public/nifty_data';
                break;
            case 'BANKNIFTY':
                $path = 'public/bnf_data';
                break;
        }

        if ($path == null){
            $this->error('path is null');
        }


        $files = Storage::disk('local')->files($path);
        foreach ($files as $f){
            $extension = substr($f,strlen($f) - 3,strlen($f));
            if ($extension === 'csv'){
                $this->info('importing : ' . $f);
                (new ExcelModelIndices($symbol))->withOutput($this->output)->import($f);
            }
        }
        $this->output->success('successfully imported...');
    }

}
