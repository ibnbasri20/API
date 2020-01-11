<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Event extends Model
{
    protected $table = "event";
    protected $fillable = ['id', 'name','category', 'sub_category', 'publisher', 'photo', 'start', 'end', 'join_id','views'];

    public function publisher()
    {
      return $this->belongsTo('App\User', 'publisher');
    }
    public function publisher_info()
    {
      return $this->belongsTo('App\UserInfo', 'publisher', 'users_id');
    }

}
