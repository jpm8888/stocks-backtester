<?php

namespace App;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ModelBhavCopyCM extends Model
{
    public $timestamps = false;
    protected $table = 'bhavcopy_cm';
    protected $guarded = [];
    protected $appends = [
        'f_date', 'f_dlv_in_crores',
        'f_traded_value', 'f_volume',
        'f_avg_dlv_in_crores', 'f_price_change',
        'f_cum_fut_oi', 'f_change_cum_fut_oi'
    ];

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


    //format date in dd-mm-yyyy
    public function getFDateAttribute(){
        try{
            return Carbon::parse($this->date)->format('d-m-Y');
        }catch (Exception $e){
            return "-";
        }
    }

    //daily total trade value in lacs.
    public function getFTradedValueAttribute(){
        try{
            return round($this->total_trade_val/10000000);
        }catch (Exception $e){
            return "-";
        }
    }

    //daily volume in lacs.
    public function getFVolumeAttribute(){
        try{
            return round($this->volume / 100000);
        }catch (Exception $e){
            return "-";
        }
    }


    //delivery in crores
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


    //5 day average of dlv in crores
    public function getFAvgDlvInCroresAttribute(){
        try{
            $dlv = DB::table('bhavcopy_cm as bcm')
                ->join('bhavcopy_delv_position as bdp', function($join){
                    $join->on('bcm.symbol', '=', 'bdp.symbol');
                    $join->on('bcm.date', '=', 'bdp.date');
                    $join->on('bcm.series', '=', 'bdp.series');
                })
                ->select('bcm.total_trade_val', 'bdp.pct_dlv_traded', 'bcm.date')
                ->where('bcm.symbol', $this->symbol)
                ->where('bcm.series', $this->series)
                ->where('bcm.date','<', $this->date)
                ->orderBy('bcm.date', 'desc')
                ->limit(5)->get();

            $total_value = 0;
            foreach ($dlv as $d){
                $value = ($d->total_trade_val * $d->pct_dlv_traded) / 1000000000;
                $total_value += $value;
            }

            return round($total_value/5);

        }catch (Exception $e){
            return "-";
        }
    }

    //price change in percent from previous day close.
    public function getFPriceChangeAttribute(){

        try{
            $prev = DB::table('bhavcopy_cm')
                ->select('close', 'date')
                ->where('symbol', $this->symbol)
                ->where('series', $this->series)
                ->where('date','<', $this->date)
                ->orderBy('date', 'desc')
                ->first();
            if ($prev) {
                $pct_change = (($this->close - $prev->close) * 100) / $prev->close;
                return round($pct_change, 2);
            }
        }catch (Exception $e){
            return "" . $e->getMessage();
        }
    }


    //cumulative open interest in futures :
    // coi = expiry_current_month + expiry_next_month;
    public function getFCumFutOiAttribute(){
        $fo = ModelBhavCopyFO::where('instrument', 'FUTSTK')
            ->where('symbol', $this->symbol)
            ->where('date', $this->date)
            ->orderBy('expiry', 'asc')
            ->limit(2)
            ->get();
        $cumulative_oi = 0;
        foreach ($fo as $f){
            $cumulative_oi += $f->oi;
        }
        return $cumulative_oi;
    }

    //change in cumulative open interest from past day.
    public function getFChangeCumFutOiAttribute(){
        $fo_current_day = ModelBhavCopyFO::where('instrument', 'FUTSTK')
            ->where('symbol', $this->symbol)
            ->where('date', $this->date)
            ->orderBy('expiry', 'asc')
            ->limit(2)
            ->get();
        $coi_current_day = 0;
        foreach ($fo_current_day as $f){
            $coi_current_day += $f->oi;
        }

        $fo_previous_day = ModelBhavCopyFO::where('instrument', 'FUTSTK')
            ->where('symbol', $this->symbol)
            ->where('date', '<' ,$this->date)
            ->orderBy('date', 'desc')
            ->orderBy('expiry', 'asc')
            ->limit(2)
            ->get();

        $coi_previous_day = 0;
        foreach ($fo_previous_day as $f){
            $coi_previous_day += $f->oi;
        }

        return $coi_current_day - $coi_previous_day;
    }

}
