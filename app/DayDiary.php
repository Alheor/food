<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DayDiary extends Model
{
    protected $table = 'diary_day';
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->hasOne('App\User', 'user_id');
    }

}
