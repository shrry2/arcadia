<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    const CREATED_AT = 'begin_at';

    public $timestamps = false;

    protected $fillable = ['user_id', 'office_id', 'begin_at', 'finish_at'];

    // デフォルト値
    protected $attributes = [
        'finish_at' => null,
    ];

    public function office()
    {
        return $this->belongsTo('App\Office');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getWorkingHourAttribute()
    {
        if (is_null($this->finish_at)) return null;

        $begin_at = new Carbon($this->begin_at);
        $finish_at = new Carbon($this->finish_at);

        return $begin_at->diffInHours($finish_at);
    }
}
