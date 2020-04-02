<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Utils\Version;
use App\ModelFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommonFetchController extends Controller
{

    public function app_info(){
        return response()->json([
           'debug' => Version::isDebug(),
           'build_version' => Version::getBuildVersion(),
           'version' => Version::getProjectVersion(),
        ]);
    }

    public function fno_stocks(){
        $data = DB::table('master_stocks_fo as msf')
            ->select('msf.*', 'f.id as fav_id')
            ->leftJoin('favorites as f', function ($join){
                $join->on('msf.symbol', '=', 'f.symbol');
                $join->where('f.user_id', '=', Auth::id());
            })
            ->orderBy('msf.weight')
            ->get();

        return response()->json($data);
    }

    public function favorites_stocks(){
        $data = ModelFavorite::where('user_id', '=', Auth::id())->orderBy('symbol')->get();
        return response()->json($data);
    }

    public function toggle_favorite(Request $request){
        try{
            $symbol = trim($request->input('symbol'));
            if ($symbol == '') return response()->json(['status' => 0, 'msg' => 'symbol cannot be empty']);

            $count = DB::table('favorites')->where('user_id', Auth::id())->where('symbol', $symbol)->count();
            $fav_id = null;
            if ($count > 0){
                DB::table('favorites')->where('user_id', Auth::id())->where('symbol', $symbol)->delete();
            }else{
                $m = new ModelFavorite();
                $m->symbol = trim($symbol);
                $m->user_id = Auth::id();
                $m->save();
                $fav_id = $m->id;
            }

            return response()->json(['status' => 1, 'msg' => 'success', 'fav_id' => $fav_id]);
        }catch (\Exception $e){
            return response()->json(['status' => 0, 'msg' => 'error : ' . $e->getMessage()]);
        }

    }
}
