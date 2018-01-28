<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';

    public function productCategory()
    {
        return $this->hasOne('App\ProductCategory',  'id', 'category_id');
    }

    public function dishes()
    {
        return $this->belongsToMany('App\Dish','dishes_products', 'id_product', 'id_dish');
    }

    public function manufacturer()
    {
        return $this->hasOne('App\Manufacturer', 'id', 'manufacturer_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function attributes()
    {
        return $this->hasOne('App\Attributes', 'id', 'attribute_id');
    }
}
