<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BapInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bap:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'BAP Installer Command';

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
        $installed = file_exists(storage_path().'/installed');

        if ($installed) {
            $this->warn('BAP Already installed');
            return true;
        }

        $this->call('migrate', ['--force'=>true]);
        $this->call('db:seed', ['--force'=>true]);

        touch(storage_path().'/installed');
    }
}
