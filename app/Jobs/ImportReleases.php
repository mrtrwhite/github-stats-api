<?php

namespace App\Jobs;

use DateTime;

use GuzzleHttp\Exception\ClientException;

use Illuminate\Support\Facades\DB;

use App\Services\GithubService;

use App\Repository;
use App\LastRelease;

class ImportReleases extends Job
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
            $release = $githubService->getLatestRelease($this->url);

            LastRelease::updateOrCreate(
                [
                    'repository_id'         => $this->repo->id
                ],
                [
                    'github_id'             => $release['id'] ?? '',
                    'name'                  => $release['name'] ?? '',
                    'tag_name'              => $release['tag_name'] ?? '',
                    'url'                   => $release['url'] ?? '',
                    'author_name'           => $release['author']['login'] ?? '',
                    'date'                  => (new DateTime($release['published_at']))->format('Y-m-d H:i:s') ?? '',
                    'repository_id'         => $this->repo->id,
                    'updated_at'            => \Carbon\Carbon::now()
                ]
            );

        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();

            if($statusCode === 403) {
                $job = (new self($this->repo, $this->url))->delay(60);
                dispatch($job);
            }
        }
    }
}
