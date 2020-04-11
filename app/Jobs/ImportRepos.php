<?php

namespace App\Jobs;

use GuzzleHttp\Exception\ClientException;

use Illuminate\Support\Facades\DB;

use App\Services\GithubService;

use App\Repository;

class ImportRepos extends Job
{
    public $page;

    public $tries = 1;

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
        try {
            $repos = $githubService->getRepositories($this->page);

            collect($repos)
                ->each(function($repo) {                
                    $repository = Repository::updateOrCreate(
                        [
                            'github_id'             => $repo['id'],
                            'name'                  => $repo['name']
                        ],
                        [
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

                    dispatch(new ImportCommits($repository, $repo['commits_url']));
                    
                    dispatch(new ImportReleases($repository, $repo['releases_url']));
                });
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();

            if($statusCode === 403) {
                $job = (new self($this->page))->delay(60);
                dispatch($job);
            }
        }
    }
}
