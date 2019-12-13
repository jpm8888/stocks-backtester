<?php

use App\ModelMasterNifty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NiftyStocksSeeder extends Seeder
{
    public function run()
    {

        ModelMasterNifty::truncate();

        $arr = [
            "AXISBANK",
            "VEDL",
            "HINDALCO",
            "SBIN",
            "MARUTI",
            "INDUSINDBK",
            "COALINDIA",
            "YESBANK",
            "TATASTEEL",
            "TCS",
            "ULTRACEMCO",
            "LT",
            "TATAMOTORS",
            "WIPRO",
            "HCLTECH",
            "HDFC",
            "NESTLEIND",
            "INFY",
            "JSWSTEEL",
            "ITC",
            "ADANIPORTS",
            "BPCL",
            "GRASIM",
            "RELIANCE",
            "NTPC",
            "M&M",
            "SUNPHARMA",
            "POWERGRID",
            "GAIL",
            "BAJAJFINSV",
            "ONGC",
            "BAJFINANCE",
            "TECHM",
            "EICHERMOT",
            "ICICIBANK",
            "INFRATEL",
            "IOC",
            "HEROMOTOCO",
            "HDFCBANK",
            "HINDUNILVR",
            "CIPLA",
            "TITAN",
            "ASIANPAINT",
            "BRITANNIA",
            "BAJAJ-AUTO",
            "UPL",
            "KOTAKBANK",
            "ZEEL",
            "BHARTIARTL",
            "DRREDDY",
        ];

        foreach ($arr as $a){
            DB::table('master_nifty')->insert([
                'symbol' => $a,
            ]);
        }
    }
}
