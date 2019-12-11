<?php

namespace App\Http\Controllers;

use App\ModelBhavCopyCM;
use App\ModelMasterBankNifty;

class ControllerBhavcopyAnalyse extends Controller
{
    public function index(){
        $data = ['div_id' => 'div_bhavcopy_analyse'];
        return view('common.react_empty', $data);
    }

    public function fetch_params(){
        return response()->json([
            'banknifty' => ModelMasterBankNifty::select('id', 'symbol as name')->get(),
        ]);
    }


    public function analyse($symbol){
        $raw = ModelBhavCopyCM::select('bhavcopy_cm.*', 'bhavcopy_delv_position.dlv_qty as dlv_qty', 'bhavcopy_delv_position.pct_dlv_traded as pct_dlv_traded')
            ->ofSymbol($symbol)
            ->withDelivery()
            ->orderBy('date')->limit(30)->get();

        return response()->json($raw);
    }


}
