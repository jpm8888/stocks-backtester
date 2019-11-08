<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelInstrument extends Model
{
    public $timestamps = false;
    protected $table = 'instrument';
    protected $guarded = [];
//    protected $appends = ['f_expiry'];


    public function scopeSegment($query, $param){
        $query->where('segment', '=', $param);
    }

    public function scopeLotSize($query, $param){
        $query->where('lot_size', '=', $param);
    }

    public function scopeTradingSymbol($query, $param){
        $query->where('tradingsymbol', 'like', $param);
    }

    public function scopeExpiryGreaterThan($query, $param){
        $query->where('expiry', '>', $param);
    }

//    public function getFExpiryAttribute(){
//        if ($this->expiry){
//            return Carbon::parse($this->expiry)->format('d-m-Y');
//        }
//        return "";
//    }
}
