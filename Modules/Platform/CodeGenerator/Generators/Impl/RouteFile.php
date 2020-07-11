<?php

namespace Modules\Platform\CodeGenerator\Generators\Impl;

use Modules\Platform\CodeGenerator\Generators\GeneratorInterface;
use Modules\Platform\CodeGenerator\Lib\FileHelper;
use Modules\Platform\CodeGenerator\Lib\StubGenerator;

/**
 * Route generator
 *
 * Class RouteFile
 * @package Modules\Platform\CodeGenerator\Generators\Impl
 */
class RouteFile implements GeneratorInterface
{

    /**
     * @param $config
     * @return bool|int
     */
    public function generate($config)
    {
        $moduleName = $config['setup']['module_name'];
        $settingsRoutes = $this->settingPart($config);

        $stubGenerator = new StubGenerator();
        $stubGenerator->setSource('Modules/Platform/CodeGenerator/Stubs/routes.stub');
        $stubGenerator->setTarget(FileHelper::newModuleFilePath($config) . '/Http/routes.php');

        return $stubGenerator->save([
            ':MODULE_NAME:' => $moduleName,
            ':MODULE_NAME_LOWERCASE:' => strtolower($moduleName),
            ':SETTINGS_ROUTES:' => $settingsRoutes
        ]);
    }

    /**
     * Generate settings route
     *
     * @param $config
     * @return string
     */
    private function settingPart($config)
    {
        $result = '';

        foreach ($config['setup']['entity'] as $entity) {
            if ($entity['type'] == 'settings') {
                $stubGenerator = new StubGenerator();
                $stubGenerator->setSource('Modules/Platform/CodeGenerator/Stubs/Partial/setting_route.stub');

                $result .= $stubGenerator->render([
                        ':ROUTE:' => strtolower($entity['name']),
                        ':ENTITY_NAME:' => $entity['name']
                    ]) . PHP_EOL;
            }
        }

        return $result;
    }
}
