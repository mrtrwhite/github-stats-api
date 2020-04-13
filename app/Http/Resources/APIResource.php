<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class APIResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name'                  => $this->full_name,
            'stars'                 => $this->stars,
            'forks'                 => $this->forks,
            'lastCommit'            => $this->lastCommit ? $this->lastCommit->message : null,
            'lastCommitDate'        => $this->lastCommit ? $this->lastCommit->commit_author_date->format('Y-m-d H:i:s') : null,
            'lastCommitTimestamp'   => $this->lastCommit ? $this->lastCommit->commit_author_date->getTimestamp() : null,
            'lastRelease'           => $this->lastRelease ? $this->lastRelease->name : null,
            'lastReleaseDate'       => $this->lastRelease ? $this->lastRelease->date->format('Y-m-d H:i:s') : null,
            'lastReleaseTimestamp'  => $this->lastRelease ? $this->lastRelease->date->getTimestamp() : null
        ];
    }
}