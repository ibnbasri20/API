<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Event extends Model
{
    protected $table = "event";
    protected $fillable = ['id', 'name','category', 'sub_category', 'publisher_id', 'photo', 'start', 'end', 'join_id','views'];

    public function user()
    {
      return $this->belongsTo('App\User', 'publisher_id');
    }
}
