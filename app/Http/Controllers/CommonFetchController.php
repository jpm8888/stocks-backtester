<?php

namespace App\Http\Controllers;

use App\ModelMasterStocksFO;

class CommonFetchController extends Controller
{
    public function fno_stocks(){
        $data = ModelMasterStocksFO::orderBy('weight')->get();
        return response()->json($data);
    }
}
