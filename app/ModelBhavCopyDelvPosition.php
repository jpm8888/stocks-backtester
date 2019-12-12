<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelBhavCopyDelvPosition extends Model
{
    public $timestamps = false;
    protected $table = 'bhavcopy_delv_position';
    protected $guarded = [];

    public function scopeOfSymbol($query, $symbol){
        return $query->where('bhavcopy_delv_position.symbol', $symbol);
    }

    public function scopeOfDate($query, $date){
        return $query->where('bhavcopy_delv_position.date', $date);
    }
}
