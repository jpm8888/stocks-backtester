<?php

namespace App\Http\Controllers;

use App\ModelBhavcopyProcessed;
use App\ModelMasterStocksFO;

class ControllerBhavcopyAnalyse extends Controller
{
    public function index(){
        $data = ['div_id' => 'div_bhavcopy_analyse'];
        return view('common.react_empty', $data);
    }

    public function fetch_params(){
        return response()->json([
            'banknifty' => ModelMasterStocksFO::select('id', 'symbol as name')->ordered()->bankNifty()->get(),
            'nifty' => ModelMasterStocksFO::select('id', 'symbol as name')->ordered()->nifty()->get(),
            'other' => ModelMasterStocksFO::select('id', 'symbol as name')->ordered()->get(),
        ]);
    }

    public function analyse($symbol){
        $raw = ModelBhavcopyProcessed::ofSymbol($symbol)->orderBy('date', 'desc')->take(45)->get();
        return response()->json($raw);
    }

    public function charts(){
        $data = ['div_id' => 'div_basic_charts'];
        return view('common_charts.react_empty', $data);
    }


}
