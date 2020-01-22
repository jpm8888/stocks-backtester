<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class ModelBhavCopyCM extends Model
{
    public $timestamps = false;
    protected $table = 'bhavcopy_cm';
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

    public function scopeIsVersion1Processed($query){
        return $query->where('v1_processed', 0);
    }
}
