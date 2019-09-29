<?php

namespace App\Http\Controllers;

use App\ModelFeatureRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ControllerFeatureRequest extends Controller
{
    public function index(){
        return view('feature.index_feature');
    }

    public function create(Request $request){
        $req = new ModelFeatureRequest();
        $req->msg = $request->input('msg');
        $req->added_by = Auth::id();
        $req->save();
        return Redirect::back()->with(
            ['success'=> 'Your ticket has been successfully created, generally I reply within 24-48 hours']
        );
    }
}
