<?php
/**
 * User: jpm
 * Date: 04/09/18
 * Time: 5:44 PM
 */

namespace App\Http\Controllers\Utils;


class Constants {
    private static $prod_server_dir = "public/uploads";
    private static $base_url = "http://shiva.dybydx.co/storage/";

    private static $cache_duration = 60 * 24; // 1 day


    /**
     * @return string
     */
    public static function getProdServerDir(): string {
        return self::$prod_server_dir;
    }


    /**
     * @return string
     */
    public static function getBaseUrl(): string {
        if (Version::isDebug()) return "http://127.0.0.1:8000/storage/";
        return self::$base_url;
    }


    /**
     * @return float|int
     */
    public static function getCacheDuration(){
        return self::$cache_duration;
    }

    public static function getDatabaseName(){
        return (env('DB_DATABASE', 'DATABASE_NAME_NOT_SET_IN_ENV'));
    }

}
