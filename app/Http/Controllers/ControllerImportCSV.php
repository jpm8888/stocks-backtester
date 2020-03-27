<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ControllerImportCSV extends Controller
{
    public function index(){
        return view('import_indices.index');
    }

    public function import(Request $request){
        $type = $request->input('type');

        try{
            $path = null;
            switch ($type){
                case 'vix':
                    $path = 'public/vix_data';
                    break;
                case 'bnf':
                    $path = 'public/bnf_data';
                    break;
                case 'nf':
                    $path = 'public/nifty_data';
                    break;
            }

            $i = 0;
            foreach($request->file('files') as $file){
                $fileName = time() . $i . '.' . $file->getClientOriginalExtension();
                Storage::putFileAs($path, $file, $fileName);
                $i++;
            }

            Artisan::call('import:vix_data');
            Artisan::call('import:indices');
            Artisan::call('delete:temp');

            return Redirect::back()->with('success', "successfully processed $i files.");
        }catch (\Exception $e){
            return Redirect::back()->with('error', "failed with error : " . $e->getMessage());
        }
    }
}
