<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = "users_chat";
    protected $fillable = ['id', 'id_user'];
}
