<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Attendance;

class Office extends Model
{
    protected $fillable = ['name'];

    public function members()
    {
        return $this->belongsToMany('App\User')->withPivot('created_at');
    }

    public function boards()
    {
        return $this->belongsToMany('App\Board', 'board_office');
    }

    public function intranets()
    {
        return $this->hasMany('App\Intranet');
    }

    public function attendances()
    {
        return $this->hasMany('App\Attendance');
    }

    public function workingMembers() {
        return $this->belongsToMany('App\User', 'attendances')
            ->wherePivot('finish_at', null)
            ->as('attendance')
            ->withPivot('begin_at', 'finish_at');
    }
}
