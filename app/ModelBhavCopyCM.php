<?php

namespace App;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;


class ModelBhavCopyCM extends Model
{
    public $timestamps = false;
    protected $table = 'bhavcopy_cm';
    protected $guarded = [];
    protected $appends = ['f_date', 'f_dlv_in_crores', 'f_traded_value', 'f_volume'];

    public function scopeOfSymbol($query, $symbol){
        return $query->where('bhavcopy_cm.symbol', $symbol);
    }

    public function scopeWithDelivery($query){
        return $query->join('bhavcopy_delv_position', function($join) {
            $join->on('bhavcopy_cm.symbol', '=', 'bhavcopy_delv_position.symbol');
            $join->on('bhavcopy_cm.date', '=', 'bhavcopy_delv_position.date');
            $join->on('bhavcopy_cm.series', '=', 'bhavcopy_delv_position.series');
        });
    }

    public function scopeOfSeriesEq($query){
        return $query->where('bhavcopy_cm.series', 'EQ');
    }

    public function getFDateAttribute(){
        try{
            return Carbon::parse($this->date)->format('d-m-Y');
        }catch (Exception $e){
            return "-";
        }
    }

    public function getFTradedValueAttribute(){
        try{
            return round($this->total_trade_val/10000000);
        }catch (Exception $e){
            return "-";
        }
    }

    public function getFVolumeAttribute(){
        try{
            return round($this->volume / 100000);
        }catch (Exception $e){
            return "-";
        }
    }


    public function getFDlvInCroresAttribute(){
        try{
            $dlv = ModelBhavCopyDelvPosition::ofSymbol($this->symbol)
                ->where('series', $this->series)
                ->ofDate($this->date)->first();
            $value = ($this->total_trade_val * $dlv->pct_dlv_traded) / 1000000000;
            return round($value);
        }catch (Exception $e){
            return "-";
        }
    }

}
