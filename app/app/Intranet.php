<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intranet extends Model
{
    protected $fillable = ['office_id', 'ip_address', 'note'];

    public function office()
    {
        return $this->belongsTo('App\Office');
    }
}
