<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    protected $fillable = ['assigned_by'];

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function assigner()
    {
        return $this->belongsTo('App\User', 'assigned_by');
    }
}
