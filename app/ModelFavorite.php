<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelFavorite extends Model
{
    public $timestamps = true;
    protected $table = 'favorites';
    protected $guarded = [];
}
