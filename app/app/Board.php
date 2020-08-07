<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $fillable = ['name'];

    public function offices()
    {
        return $this->belongsToMany('App\Office', 'board_office');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}
