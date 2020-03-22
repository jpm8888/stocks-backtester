<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelTempFile extends Model
{
    public $timestamps = true;
    protected $table = 'temp_files';
    protected $guarded = [];
}
