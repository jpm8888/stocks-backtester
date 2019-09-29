<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelReleases extends Model
{
    public $timestamps = true;
    protected $table = 'releases';
    protected $guarded = [];


    public function scopeOrdered(){
        return $this->orderBy('created_at', 'desc');
    }
}
