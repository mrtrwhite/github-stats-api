<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;

use App\Services\GithubService;

class ImportRepos extends Job
{
    public $page;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($page = 1)
    {
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GithubService $githubService)
    {
        $repos = $githubService->getRepositories($this->page);

        $formattedRepos = collect($repos)
            ->each(function($repo) {
                DB::table('repositories')
                    ->updateOrInsert(
                        [
                            'github_id'             => $repo['id'],
                            'name'                  => $repo['name']
                        ],
                        [
                            'github_id'             => $repo['id'],
                            'name'                  => $repo['name'],
                            'full_name'             => $repo['full_name'],
                            'description'           => $repo['description'] ?? '',
                            'url'                   => $repo['url'],
                            'stars'                 => $repo['stargazers_count'],
                            'watchers'              => $repo['watchers_count'],
                            'forks'                 => $repo['forks_count'],
                            'open_issues'           => $repo['open_issues'],
                            'language'              => $repo['language'] ?? '',
                            'created_at'            => \Carbon\Carbon::now(),
                            'updated_at'            => \Carbon\Carbon::now()
                        ]
                    );
            });
    }
}
