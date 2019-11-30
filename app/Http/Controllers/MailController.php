<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{

    //takes an data array with key value msg in it.
    public function send_basic_email($data, $subject) {
        Mail::send('mail', $data, function($message) use ($subject) {
            $to_email_address = 'jpm8888@gmail.com';
            $to_name = 'Jitendra Maindola';

            $from_email_address = "jpm8888@gmail.com";
            $from_name = "Jitendra Maindola";

            $message->to($to_email_address, $to_name)->subject($subject);
            $message->from($from_email_address,$from_name);
        });
    }
}
