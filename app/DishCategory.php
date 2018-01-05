<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DishCategory extends Model
{
    protected $table = 'dish_categories';
    protected $primaryKey = 'id';

    public function cats()
    {
        return $this->hasMany('App\DishCategory', 'parent_id', 'id');
    }
}
