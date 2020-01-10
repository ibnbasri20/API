<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = "messages";
    protected $fillable = ['id', 'sender_id', 'received_id', 'message'];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
