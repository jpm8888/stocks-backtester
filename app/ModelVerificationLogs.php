<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelVerificationLogs extends Model
{
    public $timestamps = false;
    protected $table = 'verification_logs';
    protected $guarded = [];
}
