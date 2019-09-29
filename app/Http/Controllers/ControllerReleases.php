<?php

namespace App\Http\Controllers;

use App\ModelReleases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ControllerReleases extends Controller
{
    public function index(){
        $user = Auth::user();
        $releases = ModelReleases::ordered()->limit(15)->get();
        $data = [
            'is_super_admin' => ($user->role_id == 1),
            'rows' => $releases,
        ];

        return view('release.index_release', $data);

    }

    public function create(Request $request){
        $user = Auth::user();
        if ($user->role_id != 1) {
            return Redirect::back()->with(
                ['error'=> 'You are not authorized to perform this function']
            );
        }
        $release = new ModelReleases();
        $release->build = $request->input('build');
        $release->msg = $request->input('msg');
        $release->save();
        return Redirect::back()->with(
            ['success'=> 'Successfully created']
        );
    }
}
