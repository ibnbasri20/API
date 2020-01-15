<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "comment";
    protected $fillable = ['id', 'id_event', 'user_id', 'comment'];

    public function event()
    {
      return $this->hasMany('App\Events', 'id_event');
    }

}
