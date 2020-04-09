<?php

namespace App\Http\Controllers;

use App\ModelBhavcopyProcessed;
use App\ModelMasterStocksFO;
use App\ModelVix;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ControllerFNOCharts extends Controller
{
    public function index(){
        $data = ['div_id' => 'div_fno_charts', 'id' => Auth::id()];
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
        $raw_symbol = $_GET['symbol'];

        $symbol = $raw_symbol;
        $second_series_name = null;

        $split = explode(':', $symbol);
        if (count($split) > 1){
            $second_series_name = $split[0];
            $symbol = $split[1];
        }

        $use_second_series = false;
        switch ($second_series_name){
            case 'FOI':
                $use_second_series = true;
                break;
            case 'OTHER':
                $use_second_series = true;
                break;
        }

        $m = ModelMasterStocksFO::where('symbol','=', "$symbol")->first();
        return response()->json([
            'name' => ($use_second_series) ? $raw_symbol : $symbol,
            'ticker' => ($use_second_series) ? $raw_symbol : $symbol,
            'description' => ($use_second_series) ? $raw_symbol : $symbol,
            'type' => $m->stock,
            //'session' => "0900-1530",
            'exchange' => "NSE",
            'listed_exchange' => "NSE",
            'timezone' => "Asia/Kolkata",

            'has_no_volume' => (!$m->has_volume),
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
        $symbol = $_GET['symbol'];
        $from = Carbon::createFromTimestamp($_GET['from'])->format('Y-m-d');
        $to = Carbon::createFromTimestamp($_GET['to'])->format('Y-m-d');
        $resolution = $_GET['resolution'];


        $second_series_name = null;
        $split = explode(':', $symbol);
        if (count($split) > 1){
            $second_series_name = $split[0];
            $symbol = $split[1];
        }

        switch ($second_series_name){
            case 'FOI':
                return $this->history_foi($symbol, $from, $to, $resolution);
                break;
            case 'OTHER':
                return $this->history_other($symbol, $from, $to, $resolution);
                break;
            default:
                return $this->history_symbol($symbol, $from, $to, $resolution);
        }

    }

    private function history_foi($symbol, $from, $to, $resolution){
        try{
            $data = ModelBhavcopyProcessed::where('symbol', '=', "$symbol")->whereBetween('date', [$from, $to])->orderBy('date', 'asc')->get();

            $s = "ok";
            $t = [];
            $c = []; //change_cum_fut_oi_val
            $o = []; //cum_pe_oi
            $h = []; //cum_ce_oi
            $l = []; // max_pe_oi_strike
            $v = []; // max_ce_oi_strike
            foreach ($data as $d){
                $t[] = Carbon::createFromFormat('Y-m-d', $d->date)->timestamp;
                $c[] = $d->change_cum_fut_oi_val;
                $o[] = $d->cum_pe_oi;
                $h[] = $d->cum_ce_oi;

                $l[] = $d->max_pe_oi_strike;
                $v[] = $d->max_ce_oi_strike;
            }
            return response()->json([
                's' => $s, 't' => $t, 'c' => $c, 'o' => $o, 'h' => $h, 'l' => $l, 'v' => $v
            ]);
        }catch (\Exception $e){
            return response()->json([
                's' => 'error',
                'error_msg' => $e->getMessage(),
            ]);
        }
    }

    private function history_other($symbol, $from, $to, $resolution){
        try{
            $data = ModelBhavcopyProcessed::where('symbol', '=', "$symbol")->whereBetween('date', [$from, $to])->orderBy('date', 'asc')->get();

            $s = "ok";
            $t = [];
            $c = []; //pcr
            $o = []; //
            $h = []; //
            $l = []; //
            $v = []; //
            foreach ($data as $d){
                $t[] = Carbon::createFromFormat('Y-m-d', $d->date)->timestamp;
                $c[] = $d->pcr;

                $o[] = $d->dlv_qty;
                $h[] = 0;
                $l[] = 0;

                $v[] = $d->volume;
            }
            return response()->json([
                's' => $s, 't' => $t, 'c' => $c, 'o' => $o, 'h' => $h, 'l' => $l, 'v' => $v
            ]);
        }catch (\Exception $e){
            return response()->json([
                's' => 'error',
                'error_msg' => $e->getMessage(),
            ]);
        }
    }


    private function history_symbol($symbol, $from, $to, $resolution){
        try{
            $data = [];
            if ($symbol === 'VIX'){
                $data = ModelVix::whereBetween('date', [$from, $to])->orderBy('date', 'asc')->get();
            }else{
                $data = ModelBhavcopyProcessed::where('symbol', '=', "$symbol")->whereBetween('date', [$from, $to])->orderBy('date', 'asc')->get();
            }


            $s = "ok";
            $t = [];
            $c = [];
            $o = [];
            $h = [];
            $l = [];
            $v = [];
            foreach ($data as $d){
                $t[] = Carbon::createFromFormat('Y-m-d', $d->date)->timestamp;
                $c[] = $d->close;
                $o[] = $d->open;
                $h[] = $d->high;
                $l[] = $d->low;
                $v[] = $d->volume;
            }
            return response()->json([
                's' => $s, 't' => $t, 'c' => $c, 'o' => $o, 'h' => $h, 'l' => $l, 'v' => $v
            ]);
        }catch (\Exception $e){
            return response()->json([
                's' => 'error',
                'error_msg' => $e->getMessage(),
            ]);
        }
    }
}
