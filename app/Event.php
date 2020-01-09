<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Event extends Model
{
    protected $table = "event";
    protected $fillable = ['id', 'name','category', 'sub_category', 'publisher_id', 'photo', 'start', 'end', 'join_id','views'];

    public function publisher()
    {
      return $this->belongsTo('App\User', 'publisher_id');
    }
    public function publisher_info()
    {
      return $this->belongsTo('App\UserInfo', 'publisher_id', 'users_id');
    }

}
