<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ModelBhavCopyDelvPosition extends Model
{
    public $timestamps = false;
    protected $table = 'bhavcopy_delv_position';
    protected $guarded = [];

    public function scopeSymbolAndDate($query, string $symbol, Carbon $date, string $series = "EQ"){
        $this->scopeOfDate($query, $date);
        $this->scopeOfSymbol($query, $symbol);
        $this->scopeOfSeries($query, $series);
        return $query;
    }

    public function scopeOfSymbol($query, string $symbol){
        return $query->where('symbol', $symbol);
    }

    public function scopeOfDate($query, Carbon $date){
        return $query->whereDate('date', $date);
    }

    public function scopeOfSeries($query, string $series = "EQ"){
        return $query->where('series', 'EQ');
    }

    public function scopeIsVerified($query){
        return $query->where('verified', 1);
    }

    public function scopeIsVersion1Processed($query){
        return $query->where('v1_processed', 1);
    }

}
