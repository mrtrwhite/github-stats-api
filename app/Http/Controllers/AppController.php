<?php

namespace App\Http\Controllers;

use App\Repository;

use Illuminate\Support\Facades\Cache;

use App\Http\Resources\APIResource;

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
        $data = Repository::with(['lastCommit', 'lastRelease'])
            ->orderBy('stars', 'DESC')
            ->limit(1000)
            ->get();

        return (APIResource::collection($data))
            ->additional([ 
                'last_updated' => $data->last()
                    ->updated_at
                    ->format('Y-m-d H:i:s') 
            ]);
    }
}
