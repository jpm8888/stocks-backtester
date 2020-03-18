<?php
/**
 * User: jpm
 * Date: 25/09/19
 * Time: 7:24 PM
 */

namespace App\Http\Controllers\Base;


class AppConstants
{
    const BUILD_VERSION = '1.1.8';

    const KITE_HOST = 'https://api.kite.trade';
    const KITE_API_KEY = 'dd5a9gbv38hfy9gx';
    const KITE_API_SECRET = 'q7nsjbg8hpsu5qbzgsi0p8kty0hvbspw';
    const KITE_VERSION = '3';

    const KITE_REQUEST_TOKEN_URL = "https://kite.trade/connect/login?v=" . self::KITE_VERSION . "&api_key=" . self::KITE_API_KEY;


    const SEGMENT_BSE = "BSE";
    const SEGMENT_CDS_FUT = "CDS-FUT";
    const SEGMENT_CDS_OPT = "CDS-OPT";
    const SEGMENT_INDICES = "INDICES";
    const SEGMENT_MCX = "MCX";
    const SEGMENT_MCX_OPT = "MCX-OPT";
    const SEGMENT_NFO_FUT = "NFO-FUT";
    const SEGMENT_NFO_OPT = "NFO-OPT";
    const SEGMENT_NSE = "NSE";

    const INSTRUMENT_TYPE_CE = 'CE';
    const INSTRUMENT_TYPE_EQ = 'EQ';
    const INSTRUMENT_TYPE_FUT = 'FUT';
    const INSTRUMENT_TYPE_PE = 'PE';


}
