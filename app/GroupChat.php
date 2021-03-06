<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupChat extends Model
{
    protected $table = "chat_group";
    protected $fillable = ['id', 'group_id','id_users', 'messages'];

    public function get_group()
    {
        return $this->belongsTo('App\EventGroup', 'group_id', 'group_id');
    }
    public function get_information_group()
    {
        return $this->belongsTo('App\Groups', 'group_id', 'id');
    }
    public function get_user(Type $var = null)
    {
        return $this->belongsTo('App\User', 'id_users', 'id');
    }
    public function user_info()
    {
      return $this->belongsTo('App\UserInfo', 'id_users', 'users_id');
    }

}
