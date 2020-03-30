<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ModelSavedCharts extends Model
{
    public $timestamps = false;
    protected $table = 'saved_charts';
    protected $guarded = [];
    protected $appends = ['timestamp', 'chart_id'];


    public function getChartIdAttribute(){
        return $this->id;
    }

    public function getTimestampAttribute(){
        return Carbon::parse($this->created_at)->timestamp;
    }
}
