<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $primarykey = 'id';
    protected $fillable = ['id', 'name','category', 'sub_category', 'publisher', 'photo', 'start', 'end', 'join_id','views'];
    public function publisher()
    {
      return $this->belongsTo('App\User', 'publisher');
    }
    public function publisher_info()
    {
      return $this->belongsTo('App\UserInfo', 'publisher', 'users_id');
    }
    public function comment()
    {
      return $this->belongsTo('App\Comment', 'id','event_id');
    }

}
