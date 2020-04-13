<?php 

namespace App\Console\Commands;

use Symfony\Component\Process\Process;

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
        // assumed static site is in static dir & nvm and npm are installed

        $process = new Process(['git', 'pull', 'origin', 'master']);
        $process->setWorkingDirectory(base_path() . '/static');
        $process->run();

        $processTwo = new Process(['npm', 'install']);
        $processTwo->setWorkingDirectory(base_path() . '/static');
        $processTwo->run();
        
        $processThree = new Process(['npm', 'install', '-g', 'gatsby']);
        $processThree->setWorkingDirectory(base_path() . '/static');
        $processThree->run();
        
        $processFour = new Process(['npm', 'run', 'deploy']);
        $processFour->setWorkingDirectory(base_path() . '/static');
        $processFour->run();
    }
}