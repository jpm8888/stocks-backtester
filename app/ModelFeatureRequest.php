<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelFeatureRequest extends Model
{
    public $timestamps = true;
    protected $table = 'feature_request';
    protected $guarded = [];
}
