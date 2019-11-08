<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelExpiryDates extends Model
{
    public $timestamps = true;
    protected $table = 'expiry_dates';
    protected $guarded = [];
}
