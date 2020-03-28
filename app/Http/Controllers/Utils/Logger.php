<?php
/**
 * User: jpm
 * Date: 13/12/19
 * Time: 7:31 pm
 */

namespace App\Http\Controllers\Utils;


use App\ModelLog;

class Logger{
    const LOG_TYPE_FNO_COPY_ADDED = "FNO_COPY_ADDED";
    const LOG_TYPE_CM_COPY_ADDED = "CM_COPY_ADDED";
    const LOG_TYPE_DLV_COPY_ADDED = "DLV_COPY_ADDED";
    const LOG_TYPE_V1_PROCESSING = "V1_PROCESSING_LOGS";
    const LOG_TYPE_INDICES_V1_PROCESSING = "V1_INDICES_PROCESSING_LOGS";
    const LOG_LOGIN_SUCCESS = "LOGIN_SUCCESS";


    //TODO disable registration page.

    public function insertLog($log_type, $msg, $added_by = 1){
        $model = new ModelLog();
        $model->log_type = $log_type;
        $model->added_by = $added_by;
        $model->msg = $msg;
        $model->save();
    }
}
