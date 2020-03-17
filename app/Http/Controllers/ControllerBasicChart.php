<?php

namespace App\Http\Controllers;

use App\ModelBhavcopyProcessed;
use App\ModelMasterStocksFO;
use Illuminate\Support\Carbon;

class ControllerBasicChart extends Controller
{
    public function index(){
        $data = ['div_id' => 'div_basic_charts'];
        return view('common_charts.react_empty', $data);
    }

    public function config(){
        return response()->json([
            'supported_resolutions'=> ['1D', '1W', '2W', '1M'],
            'supports_group_request'=> false,
            'supports_marks'=> false,
            'supports_search'=> true,
            'supports_timescale_marks'=> false,
        ]);
    }

    public function search(){
        $symbol = $_GET['query'];
        $limit = $_GET['limit'];
        $models = ModelMasterStocksFO::where('symbol','like', "%$symbol%")->limit($limit)->get();

        foreach ($models as $m){
            $m->full_name = "";
            $m->description = "";
            $m->exchange = "NSE";
            $m->ticker = $m->symbol;
            $m->type = "stock"; // or "futures" or "bitcoin" or "forex" or "index"
        }
        return response()->json($models);
    }

    public function symbols(){
        $symbol = $_GET['symbol'];

        $split = explode(':', $symbol);
        if (count($split) > 1){
            $symbol = $split[1];
        }

        $m = ModelMasterStocksFO::where('symbol','=', "$symbol")->first();
        return response()->json([
            'name' => $m->symbol,
            'ticker' => $m->symbol,
            'description' => $m->symbol,
            'type' => $m->stock,
            'session' => "0900-1530",
            'exchange' => "NSE",
            'listed_exchange' => "NSE",
            'timezone' => "Asia/Kolkata",

            'has_intraday' => false,
            'has_seconds' => false,
            'has_daily' => true,
            'data_status' => "endofday",
            'expired' => false,
            'sector' => "",
            'industry' => "",
            'currency_code' => "INR",
        ]);
    }

    public function history(){
        $symbol = $_GET['symbol'];
        $from = Carbon::createFromTimestamp($_GET['from']);
        $to = Carbon::createFromTimestamp($_GET['to']);
        $resolution = $_GET['resolution'];

        $data = ModelBhavcopyProcessed::where('symbol', '=', "$symbol")
            ->whereBetween('date', [$from, $to])->orderBy('date', 'asc')->get();


        $s = "ok";
        $t = [];
        $c = [];
        $o = [];
        $h = [];
        $l = [];
        $v = [];
        foreach ($data as $d){
            $t[] = Carbon::parse($d->date)->timestamp;
            $c[] = $d->close;
            $o[] = $d->open;
            $h[] = $d->high;
            $l[] = $d->low;
            $v[] = $d->volume;
        }
        return response()->json([
            's' => $s, 't' => $t, 'c' => $c, 'o' => $o, 'h' => $h, 'l' => $l, 'v' => $v
        ]);
    }
}
