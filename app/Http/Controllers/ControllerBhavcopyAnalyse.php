<?php

namespace App\Http\Controllers;

class ControllerBhavcopyAnalyse extends Controller
{
    public function index(){
        $data = ['div_id' => 'div_bhavcopy_analyse'];
        return view('common.react_empty', $data);
    }


}
