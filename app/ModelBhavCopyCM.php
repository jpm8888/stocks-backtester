<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ModelBhavCopyCM extends Model
{
    public $timestamps = false;
    protected $table = 'bhavcopy_cm';
    protected $guarded = [];
//    protected $appends = [
//        'f_date', 'f_dlv_in_crores',
//        'f_traded_value', 'f_volume',
//        'f_avg_dlv_in_crores', 'f_price_change',
//        'f_cum_fut_oi', 'f_change_cum_fut_oi',
//        'f_option_data', 'f_five_day_volume_avg'
//    ];

//    public function scopeOfSymbol($query, $symbol){
//        return $query->where('bhavcopy_cm.symbol', $symbol);
//    }

//    public function scopeWithDelivery($query){
//        return $query->join('bhavcopy_delv_position', function($join) {
//            $join->on('bhavcopy_cm.symbol', '=', 'bhavcopy_delv_position.symbol');
//            $join->on('bhavcopy_cm.date', '=', 'bhavcopy_delv_position.date');
//            $join->on('bhavcopy_cm.series', '=', 'bhavcopy_delv_position.series');
//        });
//    }

//    public function scopeOfSeriesEq($query){
//        return $query->where('bhavcopy_cm.series', 'EQ');
//    }


//    //format date in dd-mm-yyyy
//    public function getFDateAttribute(){
//        try{
//            return Carbon::parse($this->date)->format('d-m-Y');
//        }catch (Exception $e){
//            return "-";
//        }
//    }
//
//    //daily total trade value in lacs.
//    public function getFTradedValueAttribute(){
//        try{
//            return round($this->total_trade_val/10000000);
//        }catch (Exception $e){
//            return "-";
//        }
//    }
//
//    //daily volume in lacs.
//    public function getFVolumeAttribute(){
//        try{
//            return round($this->volume / 100000);
//        }catch (Exception $e){
//            return "-";
//        }
//    }
//
//
//    //delivery in crores
//    public function getFDlvInCroresAttribute(){
//        try{
//            $dlv = ModelBhavCopyDelvPosition::ofSymbol($this->symbol)
//                ->where('series', $this->series)
//                ->ofDate($this->date)
//                ->first();
//            $value = ($this->total_trade_val * $dlv->pct_dlv_traded) / 1000000000;
//            return round($value);
//        }catch (Exception $e){
//            return "-";
//        }
//    }
//
//
//    //5 day average of dlv in crores
//    public function getFAvgDlvInCroresAttribute(){
//        try{
//            $dlv = DB::table('bhavcopy_cm as bcm')
//                ->join('bhavcopy_delv_position as bdp', function($join){
//                    $join->on('bcm.symbol', '=', 'bdp.symbol');
//                    $join->on('bcm.date', '=', 'bdp.date');
//                    $join->on('bcm.series', '=', 'bdp.series');
//                })
//                ->select('bcm.total_trade_val', 'bdp.pct_dlv_traded', 'bcm.date')
//                ->where('bcm.date','<', $this->date)
//                ->where('bcm.symbol', $this->symbol)
//                ->where('bcm.series', $this->series)
//                ->orderBy('bcm.date', 'desc')
//                ->limit(5)->get();
//
//            $total_value = 0;
//            foreach ($dlv as $d){
//                $value = ($d->total_trade_val * $d->pct_dlv_traded) / 1000000000;
//                $total_value += $value;
//            }
//
//            return round($total_value/5);
//
//        }catch (Exception $e){
//            return "-";
//        }
//    }
//
//    //price change in percent from previous day close.
//    public function getFPriceChangeAttribute(){
//
//        try{
//            $prev = DB::table('bhavcopy_cm')
//                ->select('close', 'date')
//                ->where('date','<', $this->date)
//                ->where('symbol', $this->symbol)
//                ->where('series', $this->series)
//                ->orderBy('date', 'desc')
//                ->first();
//            if ($prev) {
//                $pct_change = (($this->close - $prev->close) * 100) / $prev->close;
//                return round($pct_change, 2);
//            }
//        }catch (Exception $e){
//            return "" . $e->getMessage();
//        }
//    }
//
//
//    //cumulative open interest in futures :
//    // coi = expiry_current_month + expiry_next_month;
//    public function getFCumFutOiAttribute(){
//        $fo = ModelBhavCopyFO::where('instrument', 'FUTSTK')
//            ->where('date', $this->date)
//            ->where('symbol', $this->symbol)
//            ->orderBy('expiry', 'asc')
//            ->limit(2)
//            ->get();
//        $cumulative_oi = 0;
//        foreach ($fo as $f){
//            $cumulative_oi += $f->oi;
//        }
//        return $cumulative_oi;
//    }
//
//    //change in cumulative open interest from past day.
//    public function getFChangeCumFutOiAttribute(){
//        $fo_current_day = ModelBhavCopyFO::where('instrument', 'FUTSTK')
//            ->where('date', $this->date)
//            ->where('symbol', $this->symbol)
//            ->orderBy('expiry', 'asc')
//            ->limit(2)
//            ->get();
//        $coi_current_day = 0;
//        foreach ($fo_current_day as $f){
//            $coi_current_day += $f->oi;
//        }
//
//        $fo_previous_day = ModelBhavCopyFO::where('instrument', 'FUTSTK')
//            ->where('date', '<' ,$this->date)
//            ->where('symbol', $this->symbol)
//            ->orderBy('date', 'desc')
//            ->orderBy('expiry', 'asc')
//            ->limit(2)
//            ->get();
//
//        $coi_previous_day = 0;
//        foreach ($fo_previous_day as $f){
//            $coi_previous_day += $f->oi;
//        }
//
//        $coi_change = $coi_current_day - $coi_previous_day;
//        $coi_pct = ($coi_previous_day == 0) ? 0 : (($coi_change * 100) / $coi_previous_day);
//
//        return [
//          'coi' => $coi_change,
//          'coi_pct' => round($coi_pct, 2),
//        ];
//    }
//
//
//    public function getFOptionDataAttribute(){
//        return [
//            'today_cum_ce_oi' => '0',
//            'today_cum_pe_oi' => '0',
//
//            'yestd_cum_ce_oi' => '0',
//            'yestd_cum_pe_oi' => '0',
//
//            'coi_pct_ce' => '0',
//            'coi_pct_pe' => '0',
//        ];
//
//
//        $current_day =  DB::table('bhavcopy_fo')
//            ->select(DB::raw('SUM(oi) as total'), 'option_type')
//            ->where('date', $this->date)
//            ->where('symbol', $this->symbol)
//            ->where('instrument', 'OPTSTK')
//            ->groupBy('option_type')
//            ->get();
//
//        $ce_total_current_day = 0;
//        $pe_total_current_day = 0;
//        foreach ($current_day as $c){
//            if ($c->option_type == 'CE'){
//                $ce_total_current_day = $c->total;
//            }else if ($c->option_type == 'PE'){
//                $pe_total_current_day = $c->total;
//            }
//        }
//
//
//
//        $prev_date = ModelBhavCopyFO::select('date')
//            ->where('date', '<', $this->date)
//            ->distinct()
//            ->orderBy('date', 'desc')
//            ->first();
//
//
//
//        $ce_total_prev_day = 0;
//        $pe_total_prev_day = 0;
//        if ($prev_date){
//            $prev_day =  DB::table('bhavcopy_fo')
//                ->select(DB::raw('SUM(oi) as total'), 'option_type')
//                ->where('date', $prev_date->date)
//                ->where('symbol', $this->symbol)
//                ->where('instrument', 'OPTSTK')
//                ->groupBy('option_type')
//                ->get();
//
//            foreach ($prev_day as $c){
//                if ($c->option_type == 'CE'){
//                    $ce_total_prev_day = $c->total;
//                }else if ($c->option_type == 'PE'){
//                    $pe_total_prev_day = $c->total;
//                }
//            }
//        }
//
//
//
//        $coi_change_ce = $ce_total_current_day - $ce_total_prev_day;
//        $coi_pct_ce = ($ce_total_prev_day == 0) ? 0 : (($coi_change_ce * 100) / $ce_total_prev_day);
//
//
//        $coi_change_pe = $pe_total_current_day - $pe_total_prev_day;
//        $coi_pct_pe = ($pe_total_prev_day == 0) ? 0 : (($coi_change_pe * 100) / $pe_total_prev_day);
//
//
//        return [
//            'today_cum_ce_oi' => $ce_total_current_day,
//            'today_cum_pe_oi' => $pe_total_current_day,
//
//            'yestd_cum_ce_oi' => $ce_total_prev_day,
//            'yestd_cum_pe_oi' => $pe_total_prev_day,
//
//            'coi_pct_ce' => round($coi_pct_ce, 2),
//            'coi_pct_pe' => round($coi_pct_pe, 2),
//        ];
//    }
//
//
//    public function getFFiveDayVolumeAvgAttribute(){
//        try{
//            $volume = DB::table('bhavcopy_cm')
//                ->select('volume')
//                ->where('date','<', $this->date)
//                ->where('symbol', $this->symbol)
//                ->where('series', $this->series)
//                ->orderBy('date', 'desc')
//                ->limit(5)->get();
//
//            $total_volume = 0;
//            foreach ($volume as $v){
//                $total_volume += $v->volume;
//            }
//
//            $today = DB::table('bhavcopy_cm')
//                ->select('volume')->where('date', $this->date)
//                ->where('symbol', $this->symbol)
//                ->where('series', $this->series)->first();
//
//
//            $avg_volume = round($total_volume / 5, 2);
//            $change = (($today->volume - $avg_volume) * 100) / $avg_volume;
//
//            return [
//              'avg_volume' => round($avg_volume),
//              'change' => round($change, 2),
//            ];
//
//        }catch (Exception $e){
//            return "-";
//        }
//    }

}
