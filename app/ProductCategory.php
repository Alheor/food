<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';
    protected $primaryKey = 'id';

    public function cats()
    {
        return $this->hasMany('App\ProductCategory', 'parent_id', 'id');
    }
}
