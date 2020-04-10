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
        dispatch(new ImportRepos());
    }
}