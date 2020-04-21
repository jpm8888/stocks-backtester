<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class DeleteTemp extends Command
{

    protected $signature = 'delete:temp';
    protected $description = 'Delete temp files and deprecated database entries.';
    public function __construct(){
        parent::__construct();
    }


    public function handle(){
        $this->delete_temp();
        $this->delete_csv_files('public/vix_data');
        $this->delete_csv_files('public/nifty_data');
        $this->delete_csv_files('public/bnf_data');
        $this->delete_logs_from_db();
        $this->info('All done for now');
    }


    private function delete_temp(){
        $this->info('Cleaning temp files');
        Storage::deleteDirectory('temp');
    }

    private function delete_csv_files($path){
        $files = Storage::disk('local')->files($path);
        foreach ($files as $f){
            $extension = substr($f,strlen($f) - 3,strlen($f));
            if ($extension === 'csv'){
                Storage::delete($f);
            }
        }
    }

    private function delete_logs_from_db(){
        $date = Carbon::now()->subDays(45);
        DB::table('log')->whereDate('created_at', '<', $date)->delete();
    }
}
