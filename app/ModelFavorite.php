<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelFavorite extends Model
{
    public $timestamps = true;
    protected $table = 'favorites';
    protected $guarded = [];
    protected $appends = ['fav_id'];


    public function getFavIdAttribute(){
        return $this->id;
    }
}
