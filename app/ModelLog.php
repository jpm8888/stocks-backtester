<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelLog extends Model
{
    public $timestamps = true;
    protected $table = 'log';
    protected $guarded = [];
}
