<?php

namespace Modules\Platform\CodeGenerator\Generators\Impl;

use Modules\Platform\CodeGenerator\Generators\GeneratorInterface;
use Modules\Platform\CodeGenerator\Lib\FileHelper;
use Modules\Platform\CodeGenerator\Lib\StubGenerator;

/**
 * DataTable Generator
 *
 * Class DatatableFile
 * @package Modules\Platform\CodeGenerator\Generators\Impl
 */
class DatatableFile implements GeneratorInterface
{
    public function generate($config)
    {
        $moduleName = $config['setup']['module_name'];
        $singularName = $config['setup']['singular_name'];

        foreach ($config['setup']['entity'] as $key => $entity) {
            $entityName = $entity['name'];

            $stubGenerator = new StubGenerator();


            if ($entity['type'] == 'main') {
                $stubGenerator->setSource('Modules/Platform/CodeGenerator/Stubs/datatable-main.stub');
                $stubGenerator->setTarget(FileHelper::newModuleFilePath($config) . "/Datatables/${entityName}Datatable.php");
            } else {
                $stubGenerator->setSource('Modules/Platform/CodeGenerator/Stubs/datatable-settings.stub');
                $stubGenerator->setTarget(FileHelper::newModuleFilePath($config) . "/Datatables/Settings/${entityName}Datatable.php");
            }

            $stubGenerator->save([
                ':MODULE_NAME:' => $moduleName,
                ':MODULE_NAME_LOWERCASE:' => strtolower($moduleName),
                ':ENTITY_NAME:' => $entityName,
                ':FIRST_FIELD:' => $this->firstColumn($entity),
                ':ENTITY_NAME_LOWER_CASE:' => strtolower($entityName),
                ':SINGULAR_NAME:' => $singularName,
                ':ROUTE:' => strtolower($entity['name'])
            ]);
        }

        return true;
    }

    private function firstColumn($entity)
    {
        foreach ($entity['properties'] as $section => $field) {
            foreach ($field as $key => $prop) {
                if ($prop['type'] == 'string') {
                    return $key;
                }
            }
        }
    }
}
