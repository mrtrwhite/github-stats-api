<?php

namespace App\Http\Controllers;

use App\Repository;

use Illuminate\Support\Facades\Cache;

class AppController extends Controller
{
    protected $githubService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('cors');
    }

    public function index()
    {             
        return Cache::remember('index', 360, function() {
            return Repository::with('lastCommit')
                ->orderBy('stars', 'DESC')
                ->limit(1000)
                ->get()
                ->map(function($repo) {
                    return [
                        'name'                  => $repo->full_name,
                        'stars'                 => $repo->stars,
                        'forks'                 => $repo->forks,
                        'lastCommit'            => $repo->lastCommit->message,
                        'lastCommitDate'        => $repo->lastCommit->commit_author_date->format('Y-m-d H:i:s'),
                        'lastCommitTimestamp'   => $repo->lastCommit->commit_author_date->getTimestamp(),
                        'lastRelease'           => $repo->lastRelease ? $repo->lastRelease->name : null,
                        'lastReleaseDate'       => $repo->lastRelease ? $repo->lastRelease->date->format('Y-m-d H:i:s') : null,
                        'lastReleaseTimestamp'  => $repo->lastRelease ? $repo->lastRelease->date->getTimestamp() : null
                    ];
                });
        });
    }
}
