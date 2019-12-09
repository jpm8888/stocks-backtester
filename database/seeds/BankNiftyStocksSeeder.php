<?php

use App\ModelMasterBankNifty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankNiftyStocksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        ModelMasterBankNifty::truncate();

        $arr = [
            "AXISBANK", "BANKBARODA", "FEDERALBNK",
            "HDFCBANK", "ICICIBANK", "IDFCFIRSTB",
            "INDUSINDBK", "KOTAKBANK", "PNB", "RBLBANK",
            "SBIN", "YESBANK"
        ];

        foreach ($arr as $a){
            DB::table('master_bank_nifty')->insert([
                'symbol' => $a,
            ]);
        }
    }
}
