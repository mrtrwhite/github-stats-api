<?php 

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\ImportRepos;

class DispatchImports extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'dispatch:imports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Dispatch the repo import jobs.";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // 1000 results/30 per page = 34
        for($i=0;$i<=34;$i++) {
            dispatch(new ImportRepos($i));
        }
    }
}