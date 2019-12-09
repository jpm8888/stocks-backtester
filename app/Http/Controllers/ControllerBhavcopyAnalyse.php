<?php

namespace App\Http\Controllers;

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


}
