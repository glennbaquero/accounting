<?php

namespace App\Console\Commands;

class Install extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup {--prod}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear database and seed data';

    /**
     * Command to run
     * @return void
     */
    protected function start()
    {
        if (!file_exists('public/storage')) {
            $this->call('storage:link');
        }
        
        $this->call('migrate:fresh');
        $this->call('db:seed');
        

        $this->call('tnt:refresh');

        if (!config('app.key')) {
            $this->call('key:generate');
        }
    }
}
