<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Reseed data
 * Class BapReseed
 * @package App\Console\Commands
 */
class BapReseed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bap:reseed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'BAP Reseed Command';

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
        $this->call('db:seed', ['--force'=>true]);
    }
}
