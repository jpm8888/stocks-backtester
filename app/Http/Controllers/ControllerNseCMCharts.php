<?php

namespace App\Http\Controllers;

use App\ModelBhavCopyNseCM;
use App\ModelMasterStocksCM;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ControllerNseCMCharts extends Controller
{
    public function index(){
        $data = ['div_id' => 'div_nse_cm_charts', 'id' => Auth::id()];
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
        $models = ModelMasterStocksCM::where('symbol','like', "%$symbol%")->limit($limit)->get();

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

        $m = ModelMasterStocksCM::where('symbol','=', "$symbol")->first();
        return response()->json([
            'name' => $m->symbol,
            'ticker' => $m->symbol,
            'description' => $m->symbol,
            'type' => 'stock',
            //'session' => "0900-1530",
            'exchange' => "NSE",
            'listed_exchange' => "NSE",
            'timezone' => "Asia/Kolkata",

            'has_no_volume' => false,
            'has_intraday' => false,
            'has_seconds' => false,
            'has_daily' => true,
            'expired' => false,
            'sector' => "NA",
            'industry' => "NA",
            'currency_code' => "INR",

            'minmov' => 1,
            'pricescale' => 100,
            'minmove2' => 0,
            'fractional' => false,
        ]);
    }

    public function history(){
        try{
            $symbol = $_GET['symbol'];
            $from = Carbon::createFromTimestamp($_GET['from'])->format('Y-m-d');
            $to = Carbon::createFromTimestamp($_GET['to'])->format('Y-m-d');
            $resolution = $_GET['resolution'];

            $data = ModelBhavCopyNseCM::where('symbol', '=', "$symbol")->whereBetween('date', [$from, $to])->orderBy('date', 'asc')->get();

            $s = "ok";
            $t = [];
            $c = [];
            $o = [];
            $h = [];
            $l = [];
            $v = [];
            $fut_oi = [];
            foreach ($data as $d){
                $t[] = Carbon::createFromFormat('Y-m-d', $d->date)->timestamp;
                $c[] = $d->close;
                $o[] = $d->open;
                $h[] = $d->high;
                $l[] = $d->low;
                $v[] = $d->volume;
                $fut_oi[] = $d->cum_fut_oi;
            }
            return response()->json([
                's' => $s, 't' => $t, 'c' => $c, 'o' => $o, 'h' => $h, 'l' => $l, 'v' => $v, 'foi' => $fut_oi
            ]);
        }catch (\Exception $e){
            return response()->json([
                's' => 'error',
                'error_msg' => $e->getMessage(),
            ]);
        }

    }
}
