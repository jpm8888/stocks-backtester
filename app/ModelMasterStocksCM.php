<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelMasterStocksCM extends Model
{
    protected $table = "master_stocks_cm";
    public $timestamps = false;
    protected $guarded = [];


    public function scopeOrdered($query){
        return $query->orderBy('symbol', 'asc');
    }
}
