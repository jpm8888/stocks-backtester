<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelIndices extends Model
{
    public $timestamps = false;
    protected $table = 'indices';
    protected $guarded = [];
}
