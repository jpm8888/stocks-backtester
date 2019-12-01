<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelBhavCopyFO extends Model
{
    public $timestamps = false;
    protected $table = 'bhavcopy_fo';
    protected $guarded = [];



//    protected static function boot() {
//        parent::boot();
//
//        static::saving(function($model){
//            $h = $model->high;
//            $l = $model->low;
//            $c = $model->close;
//            $avg = ($h + $l + $c) / 3;
//
//
//
//                $model->weight / ($model->height * $model->height);
//        });
//    }
}
