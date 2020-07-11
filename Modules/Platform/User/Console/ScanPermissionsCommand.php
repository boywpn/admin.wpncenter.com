<?php

namespace Modules\Platform\User\Console;

use Illuminate\Console\Command;
use Modules\Platform\User\Repositories\RoleRepository;
use Symfony\Component\Console\Input\InputOption;

class ScanPermissionsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bap:scan_permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan Permissions from module config files';

    /**
     * @var RoleRepository
     */
    private $roleRepo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(RoleRepository $roleRepository)
    {
        parent::__construct();
        $this->roleRepo = $roleRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Scanning for modules permissions...');

        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');


        $result = $this->roleRepo->synchModulePermissions(true);

        $this->info('Permissions Created: '.count($result['created']));
        $this->info('Permissions Deleted: '.count($result['deleted']));
    }
}
