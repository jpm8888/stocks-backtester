<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ModelBhavCopyFO extends Model
{
    public $timestamps = false;
    protected $table = 'bhavcopy_fo';
    protected $guarded = [];

    public function scopeSymbolAndDate($query, string $symbol, Carbon $date, string $instrument){
        $this->scopeOfDate($query, $date);
        $this->scopeOfSymbol($query, $symbol);
        $this->scopeOfInstrument($query, $instrument);
        return $query;
    }

    public function scopeOfSymbol($query, string $symbol){
        return $query->where('symbol', $symbol);
    }

    public function scopeOfDate($query, Carbon $date){
        return $query->whereDate('date', $date);
    }


    //$instruments => FUTIDX, FUTSTK, OPTIDX, OPTSTK
    public function scopeOfInstrument($query, string $instrument){
        return $query->where('instrument', $instrument);
    }

    //option type should be CE or PE
    public function scopeOfOptionType($query, string $option_type){
        return $query->where('option_type', $option_type);
    }

    //applicable to options only.
    public function scopeOfStrikePrice($query, string $strike_price){
        return $query->where('strike_price', $strike_price);
    }

}
