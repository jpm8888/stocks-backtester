<?php

namespace App\Http\Controllers;

class ControllerRiskCalculator extends Controller
{
    public function index(){
        $data = ['div_id' => 'div_risk_calculator'];
        return view('common.react_empty', $data);
    }
}
