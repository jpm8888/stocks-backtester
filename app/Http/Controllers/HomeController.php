<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AppConstants;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $user = Auth::user();
        if ($request->has('request_token')){
            $user->kite_request_token = $request->input('request_token');
            $user->save();
            $this->get_session_token($user->kite_request_token);
            return view('home');
        }else{
            return Redirect::to(AppConstants::KITE_REQUEST_TOKEN_URL);
        }
    }

    private function get_session_token($request_token){
        $client = new Client(['base_uri' => AppConstants::KITE_HOST, 'timeout'  => 2.0,]);
        $response = $client->post('/session/token', [
            'headers' => ['X-Kite-Version' => AppConstants::KITE_VERSION],
            'form_params' => [
                'api_key' => AppConstants::KITE_API_KEY,
                'request_token' => $request_token,
                'checksum' => hash('sha256', AppConstants::KITE_API_KEY . $request_token . AppConstants::KITE_API_SECRET),
            ]
        ]);
        dd($response->getBody()->getContents());
    }
}
