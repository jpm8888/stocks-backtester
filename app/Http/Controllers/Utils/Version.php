<?php

namespace App\Http\Controllers\Utils;
use App\Http\Controllers\Base\AppConstants;

class Version {
    private static $PROJECT_VERSION = AppConstants::BUILD_VERSION;
    private static $BUILD_VERSION = AppConstants::BUILD_VERSION;
    private static $API_VERSION = '1.0.0';
    private static $AUTHOR = 'jpm8888@gmail.com';
    const APP_TITLE = 'ShivaFNO';

    public static function getProjectVersion() {
        return self::$PROJECT_VERSION;
    }

    public static function getApiVersion() {
        return self::$API_VERSION;
    }

    public static function getAuthor() {
        return self::$AUTHOR;
    }

    public static function getBuildVersion(){
        return self::$BUILD_VERSION;
    }

    public static function isDebug(){
        return (env('APP_ENV', 'local') === 'local');
    }
}
