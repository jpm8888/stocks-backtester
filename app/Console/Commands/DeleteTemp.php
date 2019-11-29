<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
    }


    private function delete_temp(){
        $this->info('Cleaning temp files');
        Storage::deleteDirectory('temp');
        $this->info('All done for now');
    }

}
