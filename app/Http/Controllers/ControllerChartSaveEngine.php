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
            $model = ModelSavedCharts::where('chart_id' , $chart_id)->where('user_id', $_GET['user'])->first();
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

            $model = ($chart_id == 0) ? new ModelSavedCharts() : ModelSavedCharts::where('chart_id', $chart_id)->first();
            $model->name = $request->input('name');
            $model->symbol = $request->input('symbol');
            $model->resolution = $request->input('resolution');
            $model->content = $request->input('content');
            $model->user_id = $_GET['user'];
            $model->created_at = Carbon::now();
            $model->save();
            return response()->json([
                'status' => 'ok',
                'id' => $model->chart_id
            ]);
        }catch (\Exception $e){
            return response()->json([
                'status' => 'error',
                'id' => 0
            ]);
        }
    }

    public function destroy(){
        DB::table('saved_charts')->where('chart_id', $_GET['chart'])->where('user_id', $_GET['user'])->delete();
        return response()->json([
            'status' => 'ok',
            'id' => 0
        ]);
    }
}
