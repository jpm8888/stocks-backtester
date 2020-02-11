<?php
/**
 * User: jpm
 * Date: 11/02/20
 * Time: 3:42 pm
 */

namespace App\Console\Commands;


use App\Http\Controllers\MailController;
use App\ModelLog;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendLogsViaMail extends Command{
    protected $signature = 'send_mail:logs';
    protected $description = 'Send day wise logs to the user.';

    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        $mail = new MailController();
        $subject = "Logs for date : " .  Carbon::now()->format('d-m-Y');

        $date = Carbon::now();
        $logs = ModelLog::whereDate('created_at', $date)->orderBy('created_at')->get();

        if (count($logs) == 0){
            $mail->send_basic_email(['msg' => 'no logs available'], $subject);
            $this->info('no logs to send...');
        }else{
            $msg = '';
            foreach ($logs as $l){
                $msg .= $l->created_at . ' : ' . $l->id . ' -> ' . $l->log_type . ' -> ' . $l->msg . ' <br>';
            }
            $mail->send_basic_email(['msg' => $msg], $subject);
            $this->info($msg);
        }
    }
}
