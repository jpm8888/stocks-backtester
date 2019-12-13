<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelMasterNifty extends Model
{
    public $timestamps = false;
    protected $table = 'master_nifty';
    protected $guarded = [];
}
