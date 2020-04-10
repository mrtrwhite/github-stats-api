<?php

namespace App\Jobs;

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
    public function handle()
    {
        //
    }
}
