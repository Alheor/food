<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    protected $table = 'performance';
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->hasOne('App\User', 'user_id');
    }
}