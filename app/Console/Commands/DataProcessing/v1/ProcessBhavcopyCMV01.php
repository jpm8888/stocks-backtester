<?php
/**
 * User: jpm
 * Date: 18/01/20
 * Time: 7:14 pm
 */

namespace App\Console\Commands\DataProcessing\v1;


use Illuminate\Console\Command;

class ProcessBhavcopyCMV01 extends Command
{


    protected $signature = 'process:bhavcopy_v1';
    protected $description = 'Process version 1 of bhavcopy';


    private $provider;
    public function __construct(){
        parent::__construct();
        $this->provider = new DataProvider();
    }

    public function handle(){
        $fo_stocks = $this->provider->get_fo_stocks();
        $this->info($fo_stocks);

    }




}
