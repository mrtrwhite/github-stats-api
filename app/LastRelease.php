<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LastRelease extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    public function repository()
    {
        return $this->belongsTo(Repository::class);
    }
}
