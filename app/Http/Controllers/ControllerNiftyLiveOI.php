<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AppConstants;
use App\ModelInstrument;
use Carbon\Carbon;

class ControllerNiftyLiveOI extends Controller
{
    public function index(){
        $data = ['div_id' => 'div_nifty_live_oi'];
        return view('common.react_empty', $data);
    }

    public function fetch(){
        $now = Carbon::now();

        //TODO : Create cron for expiry date.
        $xyz = ModelInstrument::select('expiry as name', 'id')
            ->segment(AppConstants::SEGMENT_NFO_OPT)
            ->lotSize(75)
            ->tradingsymbol('NIFTY%')
            ->expiryGreaterThan($now)
            ->groupBy('expiry')
            ->orderBy('expiry')
            ->limit(6)
            ->get();

        $data = [
            'expiry_dates' => ['a', 'b', 'c'],
            'xyz' => $xyz,
        ];
        return response()->json($data);
    }

}
