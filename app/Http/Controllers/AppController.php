<?php

namespace App\Http\Controllers;

use DateTime;

use App\Exceptions\NoItemsException;

use App\Services\GithubService;

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

        $this->githubService = new GithubService();
    }

    public function index()
    {
        try {
            $items = $this->githubService->getRepositories();

            // $data = collect($items)->map(function($item) {
            //     $commits = $this->githubService->getCommits($item['commits_url']);

            //     return [
            //         'name'                  => $item['full_name'],
            //         'stars'                 => $item['stargazers_count'],
            //         'forks'                 => $item['forks_count'],
            //         'lastCommit'            => $commits[0],
            //         'lastCommitDate'        => $commits[0]['commit']['committer']['date'],
            //         'lastCommitTimestamp'   => (new DateTime($commits[0]['commit']['committer']['date']))->getTimestamp()
            //     ];
            // });

            // $this->writeToFile(storage_path('app/data2.json'), json_encode($data));

            $data = json_decode(file_get_contents(storage_path('app/data2.json')), true);

            return $data;

        } catch (\Exception $e) {
            dd($e);
        }
    }
}
