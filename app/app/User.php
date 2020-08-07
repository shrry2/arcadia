<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function boards()
    {
        return $this->belongsToMany('App\Board');
    }

    public function offices()
    {
        return $this->belongsToMany('App\Office')->withPivot('created_at');
    }

    public function attendances()
    {
        return $this->hasMany('App\Attendance');
    }

    public function tasksCreated()
    {
        return $this->hasMany('App\Task');
    }

    public function tasksAssigned()
    {
        return $this->belongsToMany('App\Task', 'task_assignments')
            ->as('assignment')
            ->withTimestamps();
    }

    public function tasksAssignedToSomeone()
    {
        return $this->hasManyThrough('App\Task', 'App\TaskAssignment', 'assigned_by');
    }
}
