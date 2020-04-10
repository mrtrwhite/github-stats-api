<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

use App\Exceptions\NoItemsException;

class AppController extends Controller
{
    protected $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client();

        $this->middleware('cors');
    }

    public function index()
    {
        try {
            // $items = $this->getRepositories();

            // $data = collect($items)->map(function($item) {
            //     $commits = $this->getCommits($item['commits_url']);

            //     return [
            //         'name' => $item['full_name'],
            //         'stars' => $item['stargazers_count'],
            //         'forks' => $item['forks_count'],
            //         'commits' => $commits
            //     ];
            // });

            // $this->writeToFile(storage_path('app/data2.json'), json_encode($data));

            $data = json_decode(file_get_contents(storage_path('app/data2.json')), true);

            return $data;

        } catch (\Exception $e) {
            dd($e);
        }
    }

    private function getRepositories()
    {
        $res = $this->client->request('GET', 'https://api.github.com/search/repositories', [
            'query' => [
                'q' => 'stars:>10000',
                'sort' => 'stars',
                'orderby' => 'desc'
            ]
        ]);

        $data = json_decode($res->getBody(), true);

        if(empty($data['items'])) {
            throw new NoItemsException();
        }

        return $data['items'];
    }
    
    private function getCommits($url)
    {
        $url = str_replace('{/sha}', '?q=is:public&sort=committer-date&orderby=desc', $url);

        $res = $this->client->request('GET', $url, [
            'query' => [
                'q' => 'is:public',
                'sort' => 'committer-date',
                'orderby' => 'desc'
            ]
        ]);

        return json_decode($res->getBody(), true);
    }

    private function writeToFile($file, $json)
    {
        $fp = fopen($file, 'w+');
        fwrite($fp, json_encode($json));
        fclose($fp);
    }
}
