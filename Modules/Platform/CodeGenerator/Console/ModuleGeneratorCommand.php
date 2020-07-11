<?php

namespace Modules\Platform\CodeGenerator\Console;

use Illuminate\Console\Command;
use Modules\Platform\CodeGenerator\Generators\Impl\ConfigFile;
use Modules\Platform\CodeGenerator\Generators\Impl\ControllerFile;
use Modules\Platform\CodeGenerator\Generators\Impl\DatatableFile;
use Modules\Platform\CodeGenerator\Generators\Impl\EntityFile;
use Modules\Platform\CodeGenerator\Generators\Impl\FormFile;
use Modules\Platform\CodeGenerator\Generators\Impl\LangFile;
use Modules\Platform\CodeGenerator\Generators\Impl\MigrationFile;
use Modules\Platform\CodeGenerator\Generators\Impl\RequestFile;
use Modules\Platform\CodeGenerator\Generators\Impl\RouteFile;

use Modules\Platform\CodeGenerator\Lib\FileHelper;
use Modules\Platform\CodeGenerator\Lib\Validator\EntityConfigValidator;
use Modules\Platform\CodeGenerator\Lib\Validator\ModuleConfigValidator;

/**
 * Module Generator Command
 *
 * Class ModuleGeneratorCommand
 * @package Modules\Platform\CodeGenerator\Console
 */
class ModuleGeneratorCommand extends Command
{
    protected $name = 'bap:codegen';

    protected $description = 'Generates module base on config.php';

    private $config;

    public function handle()
    {
        $module = \Module::find('codegenerator');

        $this->config = \File::getRequire('Modules/Platform/CodeGenerator/Config/generator.php');

        $this->info('Checking configration files...');

        try {


            //Check config

            $this->info('Validating module configuration.');
            ;
            $moduleValidator = new ModuleConfigValidator();
            $moduleValidator->validate($this->config);

            $this->info('Validating entity configuration.');
            ;
            $entityValidator = new EntityConfigValidator();
            $entityValidator->validate($this->config);


            FileHelper::recurseRmdir(FileHelper::newModuleFilePath($this->config));


            $this->info('Generating module files..');

            $moduleName = $this->config['setup']['module_name'];

            \Artisan::call('module:make', ['name' => [$moduleName]]);

            while (true) {
                $module = \Module::find($moduleName);
                if ($module != null) {
                    $configFile = new ConfigFile();

                    if ($configFile->generate($this->config)) {
                        $this->info('Config file generated.');
                    }

                    $routeFile = new RouteFile();
                    if ($routeFile->generate($this->config)) {
                        $this->info('Route file generated.');
                    }

                    $requestFile = new RequestFile();
                    if ($requestFile->generate($this->config)) {
                        $this->info('Request file generated.');
                    }

                    $langFile = new LangFile();
                    if ($langFile->generate($this->config)) {
                        $this->info('Lang file generated.');
                    }

                    $controllerFiles = new ControllerFile();
                    if ($controllerFiles->generate($this->config)) {
                        $this->info('Controllers files generated.');
                    }

                    $entityFile = new EntityFile();
                    if ($entityFile->generate($this->config)) {
                        $this->info('Entities files genareted.');
                    }

                    $formFile = new FormFile();
                    if ($formFile->generate($this->config)) {
                        $this->info('Form file generated');
                    }

                    $migrationFiles = new MigrationFile();
                    if ($migrationFiles->generate($this->config)) {
                        $this->info('Migration files generated');
                    }

                    $datatableFiles = new DatatableFile();
                    if ($datatableFiles->generate($this->config)) {
                        $this->info('Datatables files genareted');
                    }


                    // Datatables/ContactsDatatable.php
                    // Datatables/Settings/...
                    // Entities/...
                    // Http/Forms/...

                    // Database/Migrations/..

                    $this->info('Done!');

                    $this->info('fixing code style');

                    return true;
                }
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $this->error($e);
        }
    }
}
