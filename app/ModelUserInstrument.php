<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelUserInstrument extends Model
{
    public $timestamps = false;
    protected $table = 'user_instrument';
    protected $guarded = [];
}
