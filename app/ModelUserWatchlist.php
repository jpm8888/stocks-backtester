<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelUserWatchlist extends Model
{
    public $timestamps = false;
    protected $table = 'user_watchlist';
    protected $guarded = [];
}
