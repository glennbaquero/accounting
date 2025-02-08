<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Helpers\EnvHelpers;

class BaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Project Installation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!EnvHelpers::isDev()) {
            dd("Forbidden: Debugging mode must be set to 'true' and enviroment must be set to local to proceed");
        }

        $this->info(PHP_EOL);
        $this->warn($this->description);
        $this->info("<fg=green;>|---------------------------------------------|");
        $this->info("<fg=green;>| <fg=red;>Welcome to</> " . config('app.name') . ' <fg=red;>installation</>             |');
        $this->info("<fg=green;>|---------------------------------------------|");
        $this->info("<fg=green;>|                                             |");
        $this->info("<fg=green;>| <fg=yellow;>Running this command will do the ff</>         |");
        $this->info("<fg=green;>| <fg=red;>{$this->description}</>                |");
        $this->info("<fg=green;>|                                             |");        
        $this->info("<fg=green;>|---------------------------------------------|");

        $toggle = $this->ask("Enter project name to start the installation, type this to proceed -> <fg=red;>(</><fg=yellow;>" . config('app.name') . "<fg=red;>)</>");

        /* Fetch value of user input */
        switch($toggle) {
            case config('app.name'): $toggle = 1;
                $this->start();
            break;

            default:
                $this->info("<fg=red;>Incorrect input!" . PHP_EOL);
            break;
        }

        if($toggle == 0) {
            dd('Installion abort!');
        }
    }

    /**
     * Command to run
     * @return void
     */
    protected function start() {
        
    }
}
