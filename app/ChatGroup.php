<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatGroup extends Model
{
    protected $table = "chat_group";
    protected $fillable = ['id', 'id_event', 'user_id', 'comment'];
}
