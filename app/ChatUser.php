<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatUser extends Model
{
    protected $table = "comment";
    protected $fillable = ['id', 'message', 'user_chat_id'];
}
