<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    protected $guarded = [];

    public function lastCommit()
    {
        return $this->hasOne(LastCommit::class);
    }
    
    public function lastRelease()
    {
        return $this->hasOne(LastRelease::class);
    }
}
