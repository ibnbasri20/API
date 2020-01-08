<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupChat extends Model
{
    protected $table = "group_chat";
    protected $fillable = ['id', 'id_char','name_group', 'id_users', 'admin'];
}
