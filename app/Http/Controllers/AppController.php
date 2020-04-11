<?php

namespace App\Http\Controllers;

use App\Repository;

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
        $data = Repository::with('lastCommit')
            ->orderBy('stars', 'DESC')
            ->limit(1000)
            ->get()
            ->map(function($repo) {
                return [
                    'name'                  => $repo->full_name,
                    'stars'                 => $repo->stars,
                    'forks'                 => $repo->forks,
                    'lastCommit'            => $repo->lastCommit,
                    'lastCommitDate'        => $repo->lastCommit->commit_author_date->format('Y-m-d H:i:s'),
                    'lastCommitTimestamp'   => $repo->lastCommit->commit_author_date->getTimestamp()
                ];
            });

        return $data;
    }
}
