<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelMasterBankNifty extends Model
{
    public $timestamps = false;
    protected $table = 'master_bank_nifty';
    protected $guarded = [];
}
