<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = "event";
    protected $fillable = ['id', 'name','category', 'sub_category', 'publisher', 'photo', 'start', 'end', 'join_id','views'];  
}
