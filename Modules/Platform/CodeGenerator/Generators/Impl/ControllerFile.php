<?php

namespace Modules\Platform\CodeGenerator\Generators\Impl;

use Modules\Platform\CodeGenerator\Generators\GeneratorInterface;
use Modules\Platform\CodeGenerator\Lib\FileHelper;
use Modules\Platform\CodeGenerator\Lib\StubGenerator;

/**
 * Controller file generator
 *
 * Class ControllerFile
 * @package Modules\Platform\CodeGenerator\Generators\Impl
 */
class ControllerFile implements GeneratorInterface
{
    public function generate($config)
    {
        $moduleName = $config['setup']['module_name'];
        $singularName = $config['setup']['singular_name'];

        $stubGenerator = new StubGenerator();
        $stubGenerator->setSource('Modules/Platform/CodeGenerator/Stubs/main-controller.stub');
        $stubGenerator->setTarget(FileHelper::newModuleFilePath($config) . "/Http/Controllers/${moduleName}Controller.php");

        $moduleSettings = \View::make('codegenerator::module_settings_part');
        $moduleSettings->with('config', $config);
        $moduleSettings->with('moduleName', $moduleName);

        $moduleShowFields = \View::make('codegenerator::module_show_fields_part');
        $moduleShowFields->with('config', $config);
        $moduleSettings->with('moduleName', $moduleName);


        $stubGenerator->save([
            ':MODULE_NAME:' => $moduleName,
            ':MODULE_NAME_LOWERCASE:' => strtolower($moduleName),
            ':MODULE_SETTINGS_PART:' => $moduleSettings,
            ':MODULE_SHOW_FIELDS_PART:' => $moduleShowFields,
            ':SINGULAR_NAME:' => $singularName
        ]);

        foreach ($config['setup']['entity'] as $key => $entity) {
            if ($entity['type'] == 'settings') {
                $settingName = $entity['name'];


                $stubGenerator = new StubGenerator();
                $stubGenerator->setSource('Modules/Platform/CodeGenerator/Stubs/settings-controller.stub');
                $stubGenerator->setTarget(FileHelper::newModuleFilePath($config) . "/Http/Controllers/Settings/${settingName}Controller.php");

                $stubGenerator->save([
                    ':MODULE_NAME:' => $moduleName,
                    ':MODULE_NAME_LOWERCASE:' => strtolower($moduleName),
                    ':SETTING_NAME:' => $settingName,
                    ':SETTING_NAME_LOWER_CASE:' => strtolower($settingName),
                    ':SINGULAR_NAME:' => $singularName
                ]);
            }
        }

        return true;
    }
}
