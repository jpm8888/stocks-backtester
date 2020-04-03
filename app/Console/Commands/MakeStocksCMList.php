<?php

namespace App\Console\Commands;

use App\ModelMasterStocksCM;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class MakeStocksCMList extends Command
{

    protected $signature = 'make:stocks_cm';
    protected $description = 'Inserts data into master_stocks_cm from cash market copy';
    public function __construct(){
        parent::__construct();
    }


    public function handle(){
        $pname = 'p_' . Carbon::now()->year;
        $models = DB::select("select symbol from bhavcopy_nse_cm partition ($pname) where date = (select max(date) from bhavcopy_nse_cm)");

        if (!empty($models)){
            DB::table('master_stocks_cm')->truncate();
        }

        foreach ($models as $m){
            $s = new ModelMasterStocksCM();
            $s->symbol = $m->symbol;
            $s->save();
        }

        $count = count($models);
        $this->info("added $count records");
    }

}
