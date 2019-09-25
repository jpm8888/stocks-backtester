<?php
/**
 * User: jpm
 * Date: 25/09/19
 * Time: 7:24 PM
 */

namespace App\Http\Controllers\Base;


class AppConstants
{
    const KITE_HOST = 'https://api.kite.trade';
    const KITE_API_KEY = 'dd5a9gbv38hfy9gx';
    const KITE_API_SECRET = 'q7nsjbg8hpsu5qbzgsi0p8kty0hvbspw';
    const KITE_VERSION = '3';

    const KITE_REQUEST_TOKEN_URL = "https://kite.trade/connect/login?v=" . self::KITE_VERSION . "&api_key=" . self::KITE_API_KEY;
}
