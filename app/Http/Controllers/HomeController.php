<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AppConstants;
use App\ModelInstrument;
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

    public function logout_kite(){
        $user = Auth::user();
        $user->access_token = '';
        $user->save();
        return Redirect::to('/home');
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


    //very heavy task do not use more frequently
    public function refresh_instruments(){
        $user = Auth::user();
        $token = $user->access_token;
        $client = new Client(['base_uri' => AppConstants::KITE_HOST, 'timeout'  => 0.0,]);
        try{
            $response = $client->get('/instruments', [
                'headers' => [
                    'Accept' => 'text/csv',
                    'X-Kite-Version' => AppConstants::KITE_VERSION,
                    'Authorization' => 'Token ' . $token,
                ],
            ]);

            $data = $response->getBody()->getContents();
            $lines = explode(PHP_EOL, $data);
            ModelInstrument::query()->truncate();
            for ($i = 1; $i < count($lines) - 1; $i++){
                $array = str_getcsv($lines[$i]);
                $instrument = new ModelInstrument();
                $x = 0;
                $instrument->instrument_token = $array[$x];
                $instrument->exchange_token = $array[++$x];
                $instrument->tradingsymbol = $array[++$x];
                $instrument->name = $array[++$x];
                $instrument->last_price = $array[++$x];
                $instrument->expiry = $array[++$x];
                $instrument->strike = $array[++$x];
                $instrument->tick_size = $array[++$x];
                $instrument->lot_size = $array[++$x];
                $instrument->instrument_type = $array[++$x];
                $instrument->segment = $array[++$x];
                $instrument->exchange = $array[++$x];
                $instrument->save();
            }
            $count = ModelInstrument::count();
            return response()->json([
               'status' => 1,
               'msg' => $count . ' instruments added.',
            ]);
        }catch (Exception $e){
            return response()->json([
                'status' => 0,
                'msg' => $e->getMessage(),
            ]);
        }

    }

}
