<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    protected $table = 'dishes';
    protected $primaryKey = 'id';

    public function dishCategory()
    {
        return $this->hasOne('App\DishCategory', 'id', 'category_id');
    }

    public function products()
    {
        return $this->belongsToMany('App\Product', 'dishes_products', 'id_dish', 'id_product');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'user_id');
    }

    public function attributes()
    {
        return $this->hasOne('App\Attributes', 'id', 'attribute_id');
    }
}
