<?php 

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\ImportRepos;

class DeploySite extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'deploy:site';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Deploy the static site.";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Cache::forget('index');

        // run gh pages command in correct dir
    }
}