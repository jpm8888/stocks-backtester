<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelMasterStocksFO extends Model
{
    protected $table = "master_stocks_fo";
    public $timestamps = false;
    protected $guarded = [];


    public function scopeOrdered($query){
        return $query->orderBy('symbol', 'asc');
    }

    public function scopeBankNifty($query){
        return $query->where('in_bnf', 1);
    }

    public function scopeNifty($query){
        return $query->where('in_nf', 1);
    }
}
