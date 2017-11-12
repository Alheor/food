<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Attributes extends Model
{
    protected $table = 'attributes';
    protected $primaryKey = 'id';

    public function Dish()
    {
        return $this->belongsTo('App\Dish', 'attribute_id', 'id');
    }

    public function Product()
    {
        return $this->belongsTo('App\Product', 'attribute_id', 'id');
    }
}