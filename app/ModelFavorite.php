<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelFavorite extends Model
{
    const TYPE_FNO = 1;
    const TYPE_CM = 2;

    public $timestamps = true;
    protected $table = 'favorites';
    protected $guarded = [];
    protected $appends = ['fav_id'];


    public function getFavIdAttribute(){
        return $this->id;
    }
}
