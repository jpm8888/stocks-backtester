<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ModelVix extends Model
{
    public $timestamps = false;
    protected $table = 'vix';
    protected $guarded = [];
}
