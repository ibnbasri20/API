<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventGroup extends Model
{
    protected $table = "chat_room";
    protected $fillable = ['id', 'group_id', 'users_id', 'admin'];
    public function chat_room()
    {
      return $this->hasMany('App\GroupChat');
    }

}
