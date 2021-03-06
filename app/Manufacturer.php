<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $table = 'manufacturers';
    protected $primaryKey = 'id';

    public function dishes()
    {
        return $this->hasMany('App\Dish');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'manufacturer_id', 'id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'user_id');
    }
}
