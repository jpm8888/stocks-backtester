<?php
/**
 * User: jpm
 * Date: 18/01/20
 * Time: 7:14 pm
 */

namespace App\Console\Commands\DataProcessing;


use Illuminate\Console\Command;

class ProcessBhavcopyCMV01 extends Command
{


    protected $signature = 'process:bhavcopy_v1';
    protected $description = 'Process version 1 of bhavcopy';
    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        $this->info('all done for now');
    }
}
