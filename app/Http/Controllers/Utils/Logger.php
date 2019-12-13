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

    //TODO write migration for added_by in logger.
    //TODO disable registration page.
    //TODO login logs
    //TODO save log on downloading bhavcopy imported.
    public function insertLog($log_type, $msg){
        $model = new ModelLog();
        $model->log_type = $log_type;
        $model->msg = $msg;
        $model->save();
    }
}
