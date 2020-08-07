<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'board_id', 'note', 'user_id', 'status', 'color', 'layout', 'archived_at'];

    // 作成者
    public function creator()
    {
        return $this->belongsTo('App\User');
    }

    // 所属ボード
    public function board()
    {
        return $this->belongsTo('App\Board');
    }

    // 担当者
    public function members()
    {
        return $this->belongsToMany('App\User', 'task_assignments')
            ->as('assignment')
            ->withTimestamps();
    }
}
