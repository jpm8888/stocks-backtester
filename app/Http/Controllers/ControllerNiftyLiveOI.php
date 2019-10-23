<?php

namespace App\Http\Controllers;

class ControllerNiftyLiveOI extends Controller
{
    public function index(){
        $data = ['div_id' => 'div_nifty_live_oi'];
        return view('common.react_empty', $data);
    }

    public function fetch(){
        $data = [];
        return response()->json($data);
    }

}
