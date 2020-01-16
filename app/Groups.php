<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    protected $fillable = ['id','name'];
    public function chat_room()
    {
      return $this->hasMany('App\GroupChat');
    }
}
