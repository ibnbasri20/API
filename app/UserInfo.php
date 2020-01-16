<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{

    protected $table = "users_info";
    protected $fillable = ['users_id', 'first_name', 'last_name', 'photo', 'group_id'];
    public $timestapms = false;
    public function event()
    {
      return $this->hasMany('App\Event', 'users_id');
    }
    public function group()
    {
      return $this->hasMany('App\GroupChat', 'users_id');
    }


}
