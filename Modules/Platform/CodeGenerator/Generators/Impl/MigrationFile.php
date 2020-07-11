<?php

namespace Modules\Platform\CodeGenerator\Generators\Impl;

use Modules\Platform\CodeGenerator\Generators\GeneratorInterface;
use Modules\Platform\CodeGenerator\Lib\FileHelper;
use Modules\Platform\CodeGenerator\Lib\StubGenerator;

/**
 * Migration generator
 *
 * Class MigrationFile
 * @package Modules\Platform\CodeGenerator\Generators\Impl
 */
class MigrationFile implements GeneratorInterface
{

    /**
     * @param $config
     */
    public function generate($config)
    {
        $moduleName = $config['setup']['module_name'];
        $singularName = $config['setup']['singular_name'];

        foreach ($config['setup']['entity'] as $key => $entity) {
            $stubGenerator = new StubGenerator();
            $stubGenerator->setSource('Modules/Platform/CodeGenerator/Stubs/migration.stub');

            $migrationDate = date('Y_m_d')."_1_";


            if ($entity['type'] == 'main') {
                $entityName = $entity['name'];

                $fileName = strtolower($entityName);

                $stubGenerator->setTarget(FileHelper::newModuleFilePath($config) . "/Database/Migrations/${migrationDate}${fileName}_migration_table.php");
            } else {
                $entityName =  $entity['name'];

                $fileName = strtolower($entityName);

                $stubGenerator->setTarget(FileHelper::newModuleFilePath($config) . "/Database/Migrations/${migrationDate}${fileName}_migration_table.php");
            }

            $up = $this->up($entity);
            $down = $this->down($entity);
            $insert = $this->insert($entity);

            $stubGenerator->save([
                ':MODULE_NAME:' => $moduleName,
                ':MODULE_NAME_LOWERCASE:' => strtolower($moduleName),
                ':ENTITY_NAME:' => $entityName,
                ':TABLE_NAME:' => $entity['table'],
                ':ENTITY_TABLE:' => $entity['table'],
                ':ENTITY_NAME_LOWER_CASE:' => strtolower($entityName),
                ':MIGRATION_NAME:' => "${entityName}Migration",
                ':UP:' => $up,
                ':DOWN:' => $down,
                ':INSERT_DATA:' => $insert
            ]);
        }

        return true;
    }

    private function up($entity)
    {
        $view = \View::make('codegenerator::migration.up');
        $view->with('entity', $entity);

        return $view;
    }

    private function down($entity)
    {
        $view = \View::make('codegenerator::migration.down');
        $view->with('entity', $entity);

        return $view;
    }

    private function insert($entity)
    {
        $view = \View::make('codegenerator::migration.insert');
        $view->with('entity', $entity);

        return $view;
    }
}
