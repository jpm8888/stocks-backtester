<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AppConstants;
use Exception;
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
        $data = [
            'is_kite_login' => $user->access_token && strlen($user->access_token) != 0,
        ];
        return view('home', $data);
    }

    public function login_with_kite(Request $request){
        $user = Auth::user();
        if ($request->has('request_token')){
            $request_token = $request->input('request_token');
            if ($this->get_session_token($request_token, $user)){
                return Redirect::to('/home');
            }else{
                return Redirect::to(AppConstants::KITE_REQUEST_TOKEN_URL);
            }
        }else{
            return Redirect::to(AppConstants::KITE_REQUEST_TOKEN_URL);
        }
    }

    private function get_session_token($request_token, $user){
        $client = new Client(['base_uri' => AppConstants::KITE_HOST, 'timeout'  => 2.0,]);
        try{
            $response = $client->post('/session/token', [
                'headers' => ['X-Kite-Version' => AppConstants::KITE_VERSION],
                'form_params' => [
                    'api_key' => AppConstants::KITE_API_KEY,
                    'request_token' => $request_token,
                    'checksum' => hash('sha256', AppConstants::KITE_API_KEY . $request_token . AppConstants::KITE_API_SECRET),
                ]
            ]);
        }catch (Exception $e){
            return false;
        }

        $content = $response->getBody();
        $json = json_decode($content);
        $user->public_token = $json->data->public_token;
        $user->access_token = $json->data->access_token;
        $user->user_id = $json->data->user_id;
        $user->broker = $json->data->broker;
        $user->save();
        return true;
    }
}
