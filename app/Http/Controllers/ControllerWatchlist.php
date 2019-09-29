<?php
/**
 * User: jpm
 * Date: 29/09/19
 * Time: 4:24 PM
 */

namespace App\Http\Controllers;


class ControllerWatchlist extends Controller{

    public function __construct()
    {

    }

    public function index(){
        $data = ['div_id' => 'div_watchlist_index'];
        return view('common.react_empty', $data);
    }


    public function create(){

    }

    public function edit(){

    }

    public function update(){

    }

}
