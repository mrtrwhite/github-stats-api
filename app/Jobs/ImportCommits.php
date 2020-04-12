<?php

namespace App\Jobs;

use DateTime;

use GuzzleHttp\Exception\ClientException;

use Illuminate\Support\Facades\DB;

use App\Services\GithubService;

use App\Repository;
use App\LastCommit;

class ImportCommits extends Job
{
    public $repo;
    public $url;

    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Repository $repo, $url)
    {
        $this->repo = $repo;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GithubService $githubService)
    {
        try {
            $commits = $githubService->getCommits($this->url);

            collect($commits)
                ->take(1)
                ->each(function($commit) {
                    LastCommit::updateOrCreate(
                        [
                            'repository_id'         => $this->repo->id
                        ],
                        [
                            'sha'                   => $commit['sha'],
                            'commit_author_name'    => $commit['commit']['author']['name'] ?? '',
                            'commit_author_date'    => (new DateTime($commit['commit']['author']['date']))->format('Y-m-d H:i:s') ?? '',
                            'commit_committer_name' => $commit['commit']['committer']['name'] ?? '',
                            'commit_committer_date' => (new DateTime($commit['commit']['committer']['date']))->format('Y-m-d H:i:s') ?? '',
                            'message'               => $commit['message'] ?? '',
                            'url'                   => $commit['url'] ?? '',
                            'repository_id'         => $this->repo->id,
                            'updated_at'            => \Carbon\Carbon::now()
                        ]
                    );
                });

        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();

            if($statusCode === 403) {
                $job = (new self($this->repo, $this->url))->delay(60);
                dispatch($job);
            }
        }
    }
}
