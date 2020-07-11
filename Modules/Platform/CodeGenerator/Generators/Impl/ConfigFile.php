<?php

namespace Modules\Platform\CodeGenerator\Generators\Impl;

use Modules\Platform\CodeGenerator\Generators\GeneratorInterface;
use Modules\Platform\CodeGenerator\Lib\FileHelper;
use Modules\Platform\CodeGenerator\Lib\StubGenerator;

/**
 * Config file generator
 *
 * Class ConfigFile
 * @package Modules\Platform\CodeGenerator\Generators\Impl
 */
class ConfigFile implements GeneratorInterface
{

    /**
     * @param $config
     * @return bool|int
     */
    public function generate($config)
    {
        $moduleName = $config['setup']['module_name'];
        $privateAccess = ($config['setup']['entity_private_access'] == 1? 'true' : 'false');

        $stubGenerator = new StubGenerator();
        $stubGenerator->setSource('Modules/Platform/CodeGenerator/Stubs/config.stub');
        $stubGenerator->setTarget(FileHelper::newModuleFilePath($config).'/Config/config.php');

        return $stubGenerator->save([
            ':MODULE_NAME:' => $moduleName,
            ':PRIVATE_ACCESS:' => $privateAccess,
            ':MODULE_NAME_LOWERCASE:' => strtolower($moduleName)
        ]);
    }
}
