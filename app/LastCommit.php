<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LastCommit extends Model
{
    protected $guarded = [];

    protected $dates = ['commit_author_date', 'commit_committer_date'];

    public function repository()
    {
        return $this->belongsTo(Repository::class);
    }
}
