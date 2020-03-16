<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ModelBhavcopyProcessed extends Model
{
    public $timestamps = false;
    protected $table = 'bhavcopy_processed';
    protected $guarded = [];
    protected $appends = ['f_date'];

    public function scopeOfSymbol($query, string $symbol){
        return $query->where('symbol', $symbol);
    }

    public function scopeOfDate($query, Carbon $date){
        return $query->whereDate('date', $date);
    }

    public function getFDateAttribute(){
        try{
            return Carbon::createFromFormat('Y-m-d', $this->date)->format('d-m-Y');
        }catch (\Exception $e) {
            return "";
        }
    }

}
