<?php

namespace App\Http\Controllers;

use App\ModelSavedCharts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ControllerChartSaveEngine extends Controller
{
    public function index(){
        $chart_id = (isset($_GET['chart'])) ? $_GET['chart'] : 0;

        if ($chart_id > 0){
            $model = ModelSavedCharts::where('id' , $chart_id)->where('user_id', $_GET['user'])->first();
            return response()->json([
               'status' => 'ok',
                'data' => $model
            ]);
        }

        $models = ModelSavedCharts::where('user_id', $_GET['user'])->get();
        return response()->json([
            'status' => 'ok',
            'data' => $models
        ]);

    }

    public function store(Request $request){
        try{
            $chart_id = (isset($_GET['chart'])) ? $_GET['chart'] : 0;

            $name = $request->input('name');
            $symbol = $request->input('symbol');
            $user_id = $_GET['user'];

            $model = ($chart_id == 0) ? new ModelSavedCharts() : ModelSavedCharts::where('id', $chart_id)->first();

            if ($chart_id == 0){
                $temp = ModelSavedCharts::where('user_id', $user_id)->where('symbol', $symbol)->where('name', $name)->first();
                if ($temp) $model = $temp;
            }


            $model->name = $name;
            $model->symbol = $symbol;
            $model->resolution = $request->input('resolution');
            $model->content = $request->input('content');
            $model->user_id = $user_id;
            $model->created_at = Carbon::now();
            $model->save();
            return response()->json([
                'status' => 'ok',
                'id' => $model->chart_id
            ]);
        }catch (\Exception $e){
            return response()->json([
                'status' => 'error',
                'id' => 0,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function destroy(){
        DB::table('saved_charts')->where('id', $_GET['chart'])->where('user_id', $_GET['user'])->delete();
        return response()->json([
            'status' => 'ok',
            'id' => 0
        ]);
    }
}
