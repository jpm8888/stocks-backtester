<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelInstrument extends Model
{
    public $timestamps = false;
    protected $table = 'instrument';
    protected $guarded = [];
}
