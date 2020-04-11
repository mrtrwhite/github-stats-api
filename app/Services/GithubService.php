<?php

namespace App\Services;

use GuzzleHttp\Client;

class GithubService {
    private $auth;

    public function __construct()
    {
        $this->auth = [
            env('GITHUB_CLIENT_ID'),
            env('GITHUB_CLIENT_SECRET')
        ];
    }

    public function getRepositories($page = 1)
    {
        $client = new Client();

        $res = $client->request('GET', 'https://api.github.com/search/repositories', [
            'auth' => $this->auth,
            'query' => [
                'q' => 'stars:>10000',
                'sort' => 'stars',
                'orderby' => 'desc',
                'page' => $page
            ]
        ]);

        $data = json_decode($res->getBody(), true);

        if(empty($data['items'])) {
            throw new NoItemsException();
        }

        return $data['items'];
    }
    
    public function getCommits($url)
    {
        $client = new Client();

        $url = str_replace('{/sha}', '?q=is:public&sort=committer-date&orderby=desc', $url);

        $res = $client->request('GET', $url, [
            'auth' => $this->auth,
            'query' => [
                'q' => 'is:public',
                'sort' => 'committer-date',
                'orderby' => 'desc'
            ]
        ]);

        $data = json_decode($res->getBody(), true);

        if(empty($data)) {
            throw new NoItemsException();
        }

        return $data;
    }
    
    public function getLatestRelease($url)
    {
        $client = new Client();

        $url = str_replace('{/id}', '/latest', $url);

        $res = $client->request('GET', $url, [
            'auth' => $this->auth
        ]);

        return json_decode($res->getBody(), true);
    }

    public function writeToFile($file, $json)
    {
        $fp = fopen($file, 'w+');
        fwrite($fp, json_encode($json));
        fclose($fp);
    }
}